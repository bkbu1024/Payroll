<?php

namespace Unit\PaymentClassification;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\SalesReceipt as SalesReceiptModel;
use Payroll\Tests\TestCase;

class CommissionedClassificationTest extends TestCase
{
    use DatabaseTransactions;

    public function testCalculatePayForSalesReceipt()
    {
        /**
         | Sales Receipt Amount = 500
         | Commission Rate = 10%
         | ----------
         | Pay for this one = 50
         */

        $mocks = $this->getMockObjects([
            SalesReceiptModel::class => [
                'getAmount' => ['return' => 500]
            ],
            Employee::class => [
                'getCommissionRate' => ['return' => 10]
            ]
        ]);

        $employee = $mocks[Employee::class];
        $salesReceipt = $mocks[SalesReceiptModel::class];

        $classification = new CommissionedClassification(1000, $employee->getCommissionRate());
        $classification->setEmployee($employee);
        $netPay = $this->invokeMethod($classification, 'calculatePayForSalesReceipt', [$salesReceipt]);

        $this->assertEquals(50, $netPay);

        /*
         | Sales Receipt Amount = 1125
         | Commission Rate = 10%
         | ----------
         | Pay for this one = 112.5
         */

        $salesReceipt = $this->getMockObject(SalesReceiptModel::class, [
            'getAmount' => ['return' => 1125]
        ]);

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