<?php

namespace Integration\Transaction;

use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Factory\Model\Employee;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Factory\Transaction\Add\TimeCard as AddTimeCardFactory;
use Payroll\Factory\Transaction\PayDay as PayDayFactory;
use Payroll\Tests\Integration\Transaction\AbstractPayDayTestCase;

class PayDayHourlyTest extends AbstractPayDayTestCase
{
    public function testPayOneHourlyEmployeeNoTimeCard()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 15
        ]);

        $employee = $transaction->execute();
        $payDate = '2017-02-03'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 0);
    }

    public function testPayMoreHourlyEmployeeNoTimeCard()
    {
        $employees = $this->getEmployees(5, Employee::HOURLY);
        $netPays = [];
        foreach ($employees as $employee) {
            $netPays[$employee->getId()] = 0;
        }

        $payDate = '2017-02-03'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $this->verityMoreHourlyPayChecks($employees, $payDay, $payDate, $netPays);
    }

    public function testPayOneHourlyEmployeeOneTimeCard()
    {
        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, '2017-02-01', 2);
        $transaction->execute();

        $payDate = '2017-02-03'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 24);
    }

    public function testPayMoreHourlyEmployeeOneTimeCard()
    {
        $employees = $this->getEmployees(5, Employee::HOURLY);
        $hours = [];
        $netPays = [];

        foreach ($employees as $employee) {
            $hour = $this->faker->randomFloat(2, 1, 8);
            $hours[$employee->getId()] = $hour;

            $transaction = AddTimeCardFactory::create($employee, '2017-02-01', $hour);
            $transaction->execute();
        }

        foreach ($employees as $employee) {
            $netPays[$employee->getId()] = $hours[$employee->getId()] * $employee->getHourlyRate();
        }

        $payDate = '2017-02-03'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $this->verityMoreHourlyPayChecks($employees, $payDay, $payDate, $netPays);
    }

    public function testPayOneHourlyEmployeeMoreTimeCard()
    {
        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, '2017-01-31', 4);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-01', 2);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-02', 8);
        $transaction->execute();

        $payDate = '2017-02-03'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 168);
    }

    public function testPayMoreHourlyEmployeeMoreTimeCard()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employees = $this->getEmployees(5, Employee::HOURLY);
        $hours = [];
        $netPays = [];

        foreach ($employees as $employee) {
            $hour1 = $this->faker->randomFloat(2, 1, 8);
            $hour2 = $this->faker->randomFloat(2, 1, 8);
            $hours[$employee->getId()] = $hour1 + $hour2;

            $transaction = AddTimeCardFactory::create($employee, '2017-02-01', $hour1);
            $transaction->execute();

            $transaction = AddTimeCardFactory::create($employee, '2017-02-02', $hour2);
            $transaction->execute();
        }

        foreach ($employees as $employee) {
            $netPays[$employee->getId()] = $hours[$employee->getId()] * $employee->getHourlyRate();
        }

        $payDate = '2017-02-03'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $this->verityMoreHourlyPayChecks($employees, $payDay, $payDate, $netPays);
    }

    public function testPayOneHourlyEmployeeOvertimeOneTimeCard()
    {
        $payDate = '2017-02-03'; // Friday
        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, $payDate, 9);
        $transaction->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, (8 * 12 + (1 * (12 * 1.5))));
    }

    public function testPayMoreHourlyEmployeeOvertimeOneTimeCard()
    {
        $payDate = '2017-02-03'; // Friday
        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 15
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $transactionTc = AddTimeCardFactory::create($employee, $payDate, 9);
        $transactionTc->execute();

        $transactionTc1 = AddTimeCardFactory::create($employee1, $payDate, 12);
        $transactionTc1->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, (8 * 12 + (1 * (12 * 1.5))));

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->verifyHourlyPayCheck($payCheck1, $payDate, (8 * 15 + (4 * (15 * 1.5))));
    }

    public function testPayOneHourlyEmployeeOnWrongDate()
    {
        $payDate = '2017-02-01'; // Not friday

        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, $payDate, 9);
        $transaction->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);
    }

    public function testPayMoreHourlyEmployeeOnWrongDate()
    {
        $payDate = '2017-02-01'; // Not friday

        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 14
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $transactionTc = AddTimeCardFactory::create($employee, $payDate, 9);
        $transactionTc->execute();

        $transactionTc = AddTimeCardFactory::create($employee1, $payDate, 5);
        $transactionTc->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->assertNull($payCheck1);
    }

    public function testPayOneHourlyEmployeeWithTimeCardsSpanningTwoPayPeriods()
    {
        $payDate = '2017-02-10'; // Friday
        $dateInPreviousPeriod = '2017-02-02';

        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, $payDate, 4);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, $dateInPreviousPeriod, 5);
        $transaction->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, (4 * 12));
    }

    public function testPayMoreHourlyEmployeeWithTimeCardsSpanningTwoPayPeriods()
    {
        $payDate = '2017-02-10'; // Friday
        $dateInPreviousPeriod = '2017-02-02';

        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 12
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 11
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $transactionTcOk = AddTimeCardFactory::create($employee, $payDate, 4);
        $transactionTcOk->execute();

        $transactionTcNotOk = AddTimeCardFactory::create($employee, $dateInPreviousPeriod, 5);
        $transactionTcNotOk->execute();

        $transactionTcOk1 = AddTimeCardFactory::create($employee1, $payDate, 7);
        $transactionTcOk1->execute();

        $transactionTcNotOk1 = AddTimeCardFactory::create($employee1, $dateInPreviousPeriod, 7);
        $transactionTcNotOk1->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, (4 * 12));

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->verifyHourlyPayCheck($payCheck1, $payDate, (7 * 11));
    }
}