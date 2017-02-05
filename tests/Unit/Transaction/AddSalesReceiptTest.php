<?php

namespace Payroll\Tests\Unit\Transaction;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\Tests\TestCase;
use Payroll\Transaction\AddCommissionedEmployee;
use Payroll\Transaction\AddHourlyEmployee;
use Payroll\Transaction\AddSalesReceipt;
use Payroll\Transaction\AddTimeCard;

class AddSalesReceiptTest extends TestCase
{
    use DatabaseTransactions;

    public function testExecute()
    {
        $faker = Factory::create();
        /**
         * @var Employee
         */
        $employee = (new AddCommissionedEmployee(
            $faker->name, $faker->address,
            $faker->randomFloat(2, 10, 35),
            $faker->randomFloat(2, 30, 125)))->execute();

        $amount = $faker->randomFloat(2, 320, 1250);
        $transaction = new AddSalesReceipt(
            (new \DateTime())->format('Y-m-d'),
            $amount,
            $employee);

        $transaction->execute();

        /**
         * @var CommissionedClassification $paymentClassification
         */
        $paymentClassification = $employee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof CommissionedClassification);

        $salesReceipt = $paymentClassification->getSalesReceipt((new \DateTime())->format('Y-m-d'));
        $this->assertEquals($amount, $salesReceipt->amount);
        $this->assertEquals($employee->id, $salesReceipt->employee_id);
    }
}
