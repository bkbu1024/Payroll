<?php

namespace Payroll\Tests\Unit\Transaction;

use Faker\Factory;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Employee;
use Payroll\Factory\Employee as EmployeeFactory;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddSalesReceipt;

class AddSalesReceiptTest extends TestCase
{
    use DatabaseTransactions;

    /**
    * @var Generator
    */
    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function testExecute()
    {
        $employee = factory(Employee::class)->create(['type' => EmployeeFactory::COMMISSION]);
        $amount = $this->faker->randomFloat(2, 320, 1250);
        $transaction = new AddSalesReceipt(
            (new \DateTime())->format('Y-m-d'),
            $amount,
            $employee);

        $transaction->execute();

        /**
         * @var CommissionedClassification
         */
        $paymentClassification = $employee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof CommissionedClassification);

        /**
         * @var SalesReceipt
         */
        $salesReceipt = $paymentClassification->getSalesReceipt((new \DateTime())->format('Y-m-d'));
        $this->assertEquals($amount, $salesReceipt->getAmount());
        $this->assertEquals($employee->getId(), $salesReceipt->getEmployeeId());
    }

    public function testExecuteInvalidUser()
    {
        $employee = factory(Employee::class)->create(['type' => EmployeeFactory::HOURLY]);
        $amount = $this->faker->randomFloat(2, 320, 1250);
        $transaction = new AddSalesReceipt(
            (new \DateTime())->format('Y-m-d'),
            $amount,
            $employee);

        try {
            $transaction->execute();
            $this->fail();
        } catch (\Exception $ex) {
            $this->assertEquals('Tried to add sales receipt to non-commissioned employee', $ex->getMessage());
        }
    }
}