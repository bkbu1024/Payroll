<?php

namespace Integration\PaymentClassification;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\Employee;
use Payroll\Factory\Model\Paycheck as PaycheckFactory;
use Payroll\Paycheck;
use Payroll\Tests\TestCase;
use Payroll\Factory\Transaction\Add\SalesReceipt as AddSalesReceiptFactory;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class CommissionedClassificationTest extends TestCase
{
    use DatabaseTransactions;

    public function testCalculatePay()
    {
        $salary = 1200;

        /**
         * @var Employee $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => $salary, 'commissionRate' => 10]);

        $employee = $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-01-01', 1000);
        $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-01-06', 1000);
        $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-01-12', 1000);
        $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-01-13', 1000);
        $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-01-14', 1000);
        $transaction->execute();

        $paycheck = PaycheckFactory::create('2017-01-13');
        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals($salary + 400, $netPay);
    }

    public function testCalculatePayNoSalesReceipt()
    {
        $salary = 1100;

        /**
         * @var Employee $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => $salary, 'commissionRate' => 10]);

        $employee = $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-01-02', 1000);
        $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-01-03', 1000);
        $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-01-12', 1000);
        $transaction->execute();

        $transaction = AddSalesReceiptFactory::create($employee, '2017-01-13', 1000);
        $transaction->execute();

        $paycheck = new Paycheck([
            'date' => '2017-02-10'
        ]);

        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals($salary, $netPay);
    }
}