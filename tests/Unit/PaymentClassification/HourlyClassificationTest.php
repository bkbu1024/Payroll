<?php

namespace Unit\PaymentClassification;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\Tests\TestCase;
use Payroll\TimeCard;

class HourlyClassificationTest extends TestCase
{
    use DatabaseTransactions;

    public function testCalculatePayForTimeCard()
    {
        $timeCard = $this->getMockObject(TimeCard::class, ['getHours' => ['return' => 5]]);

        $classification = new HourlyClassification(15);
        $netPay = $this->invokeMethod($classification, 'calculatePayForTimeCard', [$timeCard]);
        $this->assertEquals(75, $netPay);

        $timeCard = $this->getMockObject(TimeCard::class, ['getHours' => ['return' => 7.5]]);
        $netPay = $this->invokeMethod($classification, 'calculatePayForTimeCard', [$timeCard]);
        $this->assertEquals(112.5, $netPay);
    }

    public function testCalculatePayForTimeCardWithOvertime()
    {
        $timeCard = $this->getMockObject(TimeCard::class, ['getHours' => ['return' => 10]]);
        $classification = new HourlyClassification(15);
        $netPay = $this->invokeMethod($classification, 'calculatePayForTimeCard', [$timeCard]);
        $this->assertEquals(8 * 15 + (2 * (15 * 1.5)), $netPay);

        $timeCard = $this->getMockObject(TimeCard::class, ['getHours' => ['return' => 12]]);
        $netPay = $this->invokeMethod($classification, 'calculatePayForTimeCard', [$timeCard]);
        $this->assertEquals(8 * 15 + (4 * (15 * 1.5)), $netPay);
    }
}