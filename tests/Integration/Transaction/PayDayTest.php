<?php

namespace Payroll\Tests\Integration\Transaction;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Contract\Paycheck;
use Payroll\Factory\Model\Employee;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Factory\Transaction\Add\TimeCard as AddTimeCardFactory;
use Payroll\Factory\Transaction\PayDay as PayDayFactory;
use Payroll\Tests\TestCase;

class PayDayTest extends TestCase
{
    use DatabaseTransactions;

    public function testPayOneSalariedEmployee()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200
        ]);

        $employee = $transaction->execute();

        $payDate = '2017-01-31';
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();
        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNotNull($payCheck);

        $this->assertEquals($payDate, $payCheck->getDate());
        $this->assertEquals($employee->getSalary(), $payCheck->getNetPay());
        $this->assertEquals('HOLD', $payCheck->getType());
    }

    public function testPayMoreSalariedEmployee()
    {
        $employees = $this->getEmployees(5, Employee::SALARIED);

        $payDate = '2017-01-31';
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payChecks = [];
        foreach ($employees as $employee) {
            $payChecks[$employee->getId()] = $payDay->getPayCheck($employee->getId());
        }

        $this->verifyMonthlyPaychecks($employees, $payChecks, $payDate);
    }

    public function testPayOneSalariedEmployeeOnWrongDate()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200
        ]);

        $employee = $transaction->execute();
        $payDate = '2017-01-27';

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);
    }

    public function testPayMoreSalariedEmployeeOnWrongDate()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2200
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 2500
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $payDate = '2017-01-27';

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->assertNull($payCheck1);
    }

    // ----------- Hourly Employees ---------------

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

    protected function verifyHourlyPayCheck(Paycheck $payCheck, $payDate, $netPay)
    {
        $this->assertNotNull($payCheck);
        $this->assertEquals($payDate, $payCheck->getDate());
        $this->assertEquals($netPay, $payCheck->getNetPay());
        $this->assertEquals('HOLD', $payCheck->getType());
    }

    protected function getEmployees($count, $type)
    {
        $employees = [];
        for ($i = 0; $i < $count; $i++) {
            if ($type == Employee::COMMISSION) {
                $transaction = AddEmployeeFactory::create([
                    'name' => $this->faker->name, 'address' => $this->faker->address,
                    'salary' => $this->faker->randomFloat(2, 2000, 3300), 'commissionRate' => $this->faker->randomFloat(2, 10, 30)
                ]);
            } elseif ($type == Employee::SALARIED) {
                $transaction = AddEmployeeFactory::create([
                    'name' => $this->faker->name, 'address' => $this->faker->address,
                    'salary' => $this->faker->randomFloat(2, 2000, 3300)
                ]);
            } elseif ($type == Employee::HOURLY) {
                $transaction = AddEmployeeFactory::create([
                    'name' => $this->faker->name, 'address' => $this->faker->address,
                    'hourlyRate' => $this->faker->randomFloat(2, 12, 35)
                ]);
            }

            $employee = $transaction->execute();
            $employees[$employee->getId()] = $employee;
        }

        return $employees;
    }

    protected function verifyMonthlyPaychecks(array $employees, array $payChecks, $payDate)
    {
        $this->assertNotEmpty($payChecks);
        foreach ($payChecks as $id => $payCheck) {
            $this->assertEquals($payDate, $payCheck->getDate());
            $this->assertEquals($employees[$id]->getSalary(), $payCheck->getNetPay());
            $this->assertEquals('HOLD', $payCheck->getType());
        }
    }

    protected function verityMoreHourlyPayChecks($employees, $payDay, $payDate, $netPays)
    {
        foreach ($employees as $employee) {
            $payCheck = $payDay->getPayCheck($employee->getId());
            $this->verifyHourlyPayCheck($payCheck, $payDate, $netPays[$employee->getId()]);
        }
    }
}