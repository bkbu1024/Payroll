<?php

namespace Payroll\Tests\Integration\Transaction;

use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Factory\Model\Employee;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Factory\Transaction\Add\SalesReceipt as AddSalesReceiptFactory;
use Payroll\Factory\Transaction\Add\TimeCard as AddTimeCardFactory;
use Payroll\Factory\Transaction\PayDay as PayDayFactory;

class PayDayCommissionTest extends AbstractPayDayTestCase
{
    public function testPayOneCommissionEmployeeNoSalesReceipt()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 10
        ]);

        $employee = $transaction->execute();
        $payDate = '2017-02-10'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyCommissionPayCheck($payCheck, $payDate, 1200);
    }

    public function testPayMoreCommissionEmployeeNoTimeCard()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 10
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1500, 'commissionRate' => 10
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $payDate = '2017-02-10'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyCommissionPayCheck($payCheck, $payDate, 1200);

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->verifyCommissionPayCheck($payCheck1, $payDate, 1500);
    }

    public function testPayOneCommissionEmployeeOneSalesReceipt()
    {
        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddSalesReceiptFactory::create($employee, '2017-02-01', 1320);
        $transaction->execute();

        $payDate = '2017-02-10'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 1200 +  158.4);
    }

    public function testPayOneCommissionEmployeeMoreSalesReceipt()
    {
        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 12
        ]);

        $employee = $transaction->execute();
        $transaction = AddSalesReceiptFactory::create($employee, '2017-02-01', 1320);
        $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-02-02', 1100);
        $transaction->execute();

        $payDate = '2017-02-10'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 1200 + 158.4 + 132);
    }

    public function testPayMoreCommissionEmployeeOneSalesReceipt()
    {
        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 12
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1300, 'commissionRate' => 10
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $transactionSr = AddSalesReceiptFactory::create($employee, '2017-02-01', 1320);
        $transactionSr->execute();

        $transactionSr1 = AddSalesReceiptFactory::create($employee1, '2017-02-02', 1100);
        $transactionSr1->execute();

        $payDate = '2017-02-10'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 1200 + 158.4);

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->verifyHourlyPayCheck($payCheck1, $payDate, 1300 + 110);
    }

    public function testPayMoreCommissionEmployeeMoreSalesReceipt()
    {
        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 12
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1300, 'commissionRate' => 10
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $transactionSr = AddSalesReceiptFactory::create($employee, '2017-02-01', 1320);
        $transactionSr->execute();

        $transactionSr1 = AddSalesReceiptFactory::create($employee, '2017-02-02', 1100);
        $transactionSr1->execute();

        $transactionSr2 = AddSalesReceiptFactory::create($employee1, '2017-02-03', 1320);
        $transactionSr2->execute();

        $transactionSr2 = AddSalesReceiptFactory::create($employee1, '2017-02-04', 1100);
        $transactionSr2->execute();

        $payDate = '2017-02-10'; // Friday
        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 1200 + 158.4 + 132);

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->verifyHourlyPayCheck($payCheck1, $payDate, 1300 + 132 + 110);
    }

    public function testPayOneCommissionEmployeeOnWrongDate()
    {
        $payDate = '2017-02-01'; // Not friday

        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1110, 'commissionRate' => 14
        ]);

        $employee = $transaction->execute();
        $transactionSr = AddSalesReceiptFactory::create($employee, '2017-02-01', 990);
        $transactionSr->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);
    }

    public function testPayMoreCommissionEmployeeOnWrongDate()
    {
        $payDate = '2017-02-01'; // Not friday

        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1110, 'commissionRate' => 14
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1500, 'commissionRate' => 10
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $transactionSr = AddSalesReceiptFactory::create($employee, '2017-02-01', 990);
        $transactionSr->execute();

        $transactionSr1 = AddSalesReceiptFactory::create($employee1, '2017-02-01', 875);
        $transactionSr1->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->assertNull($payCheck);

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->assertNull($payCheck1);
    }

    // @todo
    public function testPayOneCommissionedEmployeeWithSalesReceiptsSpanningTwoPayPeriods()
    {
        $payDate = '2017-02-10'; // Friday
        $dateInPreviousPeriod = '2017-01-18';

        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1000, 'commissionRate' => 20
        ]);

        $employee = $transaction->execute();
        $transactionSr = AddSalesReceiptFactory::create($employee, $payDate, 990);
        $transactionSr->execute();

        $transactionSr = AddSalesReceiptFactory::create($employee, $dateInPreviousPeriod, 1990);
        $transactionSr->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 1000 + 198);
    }

    public function testPayMoreCommissionedEmployeeWithsalesCardsSpanningTwoPayPeriods()
    {
        $payDate = '2017-02-10'; // Friday
        $dateInPreviousPeriod = '2017-01-18';

        /**
         * @var EmployeeContract $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1000, 'commissionRate' => 20
        ]);

        $transaction1 = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1200, 'commissionRate' => 15
        ]);

        $employee = $transaction->execute();
        $employee1 = $transaction1->execute();

        $transactionSr = AddSalesReceiptFactory::create($employee, $payDate, 990);
        $transactionSr->execute();

        $transactionSr = AddSalesReceiptFactory::create($employee, $dateInPreviousPeriod, 1990);
        $transactionSr->execute();

        $transactionSr = AddSalesReceiptFactory::create($employee1, $payDate, 1200);
        $transactionSr->execute();

        $transactionSr = AddSalesReceiptFactory::create($employee1, $dateInPreviousPeriod, 1990);
        $transactionSr->execute();

        $payDay = PayDayFactory::createPayDay($payDate);
        $payDay->execute();

        $payCheck = $payDay->getPayCheck($employee->getId());
        $this->verifyHourlyPayCheck($payCheck, $payDate, 1000 + 198);

        $payCheck1 = $payDay->getPayCheck($employee1->getId());
        $this->verifyHourlyPayCheck($payCheck1, $payDate, 1200 + 180);
    }
}