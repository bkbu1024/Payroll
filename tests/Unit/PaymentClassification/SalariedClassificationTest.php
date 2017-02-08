<?php

namespace Unit\PaymentClassification;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Paycheck;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\Tests\TestCase;

class SalariedClassificationTest extends TestCase
{
    use DatabaseTransactions;

    public function testCalculatePay()
    {
        $classification = new SalariedClassification(2300);

        $paycheck = new Paycheck(['date' => '2017-01-31']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-02-28']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-03-31']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-04-30']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-05-31']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-06-30']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-07-31']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-08-31']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-09-30']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-10-31']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-11-30']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);

        $paycheck = new Paycheck(['date' => '2017-12-31']);
        $netPay = $classification->calculatePay($paycheck);
        $this->assertEquals(2300, $netPay);
    }
}