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
        $employee = new Employee;
        $employee->name = $this->faker->name;
        $employee->address = $this->faker->address;
        $employee->salary = $this->faker->randomFloat(2, 1000, 2000);
        $employee->commission_rate = $this->faker->randomFloat(2, 10, 20);
        $employee->type = EmployeeFactory::COMMISSION;
        $employee->save();

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
        $employee = new Employee;
        $employee->name = $this->faker->name;
        $employee->address = $this->faker->address;
        $employee->hourly_rate = $this->faker->randomFloat(2, 10, 20);
        $employee->type = EmployeeFactory::HOURLY;
        $employee->save();

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