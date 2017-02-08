<?php

namespace Unit\PaymentSchedule;

use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\Tests\TestCase;

class MonthlyScheduleTest extends TestCase
{
    public function testIsPayDayTrue()
    {
        // Last day of month
        $schedule = new MonthlySchedule;
        $this->assertTrue($schedule->isPayDay('2017-01-31'));
        $this->assertTrue($schedule->isPayDay('2017-02-28'));
        $this->assertTrue($schedule->isPayDay('2017-03-31'));
        $this->assertTrue($schedule->isPayDay('2017-04-30'));
        $this->assertTrue($schedule->isPayDay('2017-05-31'));
        $this->assertTrue($schedule->isPayDay('2017-06-30'));
        $this->assertTrue($schedule->isPayDay('2017-07-31'));
        $this->assertTrue($schedule->isPayDay('2017-08-31'));
        $this->assertTrue($schedule->isPayDay('2017-09-30'));
        $this->assertTrue($schedule->isPayDay('2017-10-31'));
        $this->assertTrue($schedule->isPayDay('2017-11-30'));
        $this->assertTrue($schedule->isPayDay('2017-12-31'));
    }

    public function testIsPayDayFalse()
    {
        $schedule = new MonthlySchedule;
        $this->assertFalse($schedule->isPayDay('2017-01-30'));
        $this->assertFalse($schedule->isPayDay('2017-02-29'));
        $this->assertFalse($schedule->isPayDay('2017-03-01'));
        $this->assertFalse($schedule->isPayDay('2017-04-14'));
        $this->assertFalse($schedule->isPayDay('2017-05-30'));
        $this->assertFalse($schedule->isPayDay('2017-06-29'));
        $this->assertFalse($schedule->isPayDay('2017-07-11'));
        $this->assertFalse($schedule->isPayDay('2017-08-01'));
        $this->assertFalse($schedule->isPayDay('2017-09-31'));
        $this->assertFalse($schedule->isPayDay('2017-10-30'));
        $this->assertFalse($schedule->isPayDay('2017-11-14'));
        $this->assertFalse($schedule->isPayDay('2017-12-27'));
    }
}