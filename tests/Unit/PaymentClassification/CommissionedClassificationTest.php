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
}