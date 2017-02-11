<?php

namespace Payroll\Tests\Integration\Transaction\Add;

use DateTime;
use Exception;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\SalesReceipt;
use Payroll\Contract\Employee as EmployeeContract;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\Tests\TestCase;
use Payroll\Factory\Transaction\Add\Employee as AddTransactionFactory;
use Payroll\Factory\Transaction\Add\SalesReceipt as AddSalesReceiptFactory;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class AddSalesReceiptTest extends TestCase
{
    use DatabaseTransactions;

    public function testExecute()
    {
        $faker = Factory::create();
        /**
         * @var EmployeeContract
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $faker->name, 'address' => $faker->address,
            'salary' => 1200, 'commissionRate' => 10]);

        $employee = $transaction->execute();
        $amount = $faker->randomFloat(2, 320, 1250);

        $transaction = AddSalesReceiptFactory::create($employee, date('Y-m-d'), $amount);
        $transaction->execute();

        /**
         * @var CommissionedClassification $paymentClassification
         */
        $paymentClassification = $employee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof CommissionedClassification);

        /**
         * @var SalesReceipt $salesReceipt
         */
        $salesReceipt = $paymentClassification->getSalesReceipt((new DateTime())->format('Y-m-d'));
        $this->assertEquals($amount, $salesReceipt->getAmount());
        $this->assertEquals($employee->getId(), $salesReceipt->getEmployeeId());

        $this->assertDatabaseHas('sales_receipts', $salesReceipt->toArray());
    }

    public function testExecuteInvalidEmployee()
    {
        $faker = Factory::create();
        /**
         * @var EmployeeContract
         */
        $employee = AddTransactionFactory::create([
            'name' => $faker->name, 'address' => $faker->address,
            'salary' => $faker->randomFloat(2, 1000, 3000)
        ])->execute();

        $amount = $faker->randomFloat(2, 320, 1250);

        try {
            $transaction = AddSalesReceiptFactory::create($employee, date('Y-m-d'), $amount);
            $transaction->execute();
            $this->fail();
        } catch (Exception $ex) {
            $this->assertEquals('Tried to add sales receipt to non-commissioned employee', $ex->getMessage());
        }
    }
}
