<?php

namespace Unit\PaymentClassification;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\SalesReceipt;
use Payroll\Employee;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\SalesReceipt as SalesReceiptModel;
use Payroll\Tests\TestCase;

class CommissionedClassificationTest extends TestCase
{
    use DatabaseTransactions;

    public function testCalculatePayForSalesReceipt()
    {
        $faker = Factory::create();
        /**
         * @var Employee $employee
         */
        $employee = factory(Employee::class)->create([
            'name'  => $faker->name,
            'address' => $faker->address,
            'salary' => 1200,
            'type' => EmployeeFactory::COMMISSION,
            'commission_rate' => 10
        ]);

        /**
         * @var SalesReceipt $salesReceipt
         */
        $salesReceipt = factory(SalesReceiptModel::class)->create([
            'employee_id' => $employee->getId(),
            'date' => date('Y-m-d'),
            'amount' => 500
        ]);

        $classification = new CommissionedClassification($employee->getSalary(), $employee->getCommissionRate());
        $classification->setEmployee($employee);
        $netPay = $this->invokeMethod($classification, 'calculatePayForSalesReceipt', [$salesReceipt]);

        $this->assertEquals(50, $netPay);

        $salesReceipt->setAmount(1125);
        $netPay = $this->invokeMethod($classification, 'calculatePayForSalesReceipt', [$salesReceipt]);
        $this->assertEquals(112.5, $netPay);
    }

    public function testIsInPayPeriodOk()
    {
        $classification = new CommissionedClassification(1200, 10);

        // paydate: 02-10 in periods: 01-28 .. 02-10

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-28', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-29', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-30', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-31', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-01', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-02', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-03', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-04', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-05', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-06', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-07', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-08', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-09', '2017-02-10']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-10', '2017-02-10']);
        $this->assertTrue($isIn);
    }

    public function testIsInPayPeriodNotOk()
    {
        $classification = new CommissionedClassification(1200, 10);

        // paydate: 02-03 in perios: 01-28 .. 02-03

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-12', '2017-02-10']);
        $this->assertFalse($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-27', '2017-02-10']);
        $this->assertFalse($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-11', '2017-02-10']);
        $this->assertFalse($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-24', '2017-02-10']);
        $this->assertFalse($isIn);
    }
}