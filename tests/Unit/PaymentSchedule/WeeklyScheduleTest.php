<?php

namespace Unit\PaymentSchedule;

use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Tests\TestCase;

class WeeklyScheduleTest extends TestCase
{
    public function testIsPayDayTrue()
    {
        // Every Friday
        $schedule = new WeeklySchedule;
        $this->assertTrue($schedule->isPayDay('2017-01-06'));
        $this->assertTrue($schedule->isPayDay('2017-01-27'));
        $this->assertTrue($schedule->isPayDay('2017-02-10'));
        $this->assertTrue($schedule->isPayDay('2017-03-31'));
        $this->assertTrue($schedule->isPayDay('2017-04-28'));
        $this->assertTrue($schedule->isPayDay('2017-05-19'));
        $this->assertTrue($schedule->isPayDay('2017-06-02'));
        $this->assertTrue($schedule->isPayDay('2017-07-07'));
        $this->assertTrue($schedule->isPayDay('2017-08-04'));
        $this->assertTrue($schedule->isPayDay('2017-09-29'));
        $this->assertTrue($schedule->isPayDay('2017-10-20'));
        $this->assertTrue($schedule->isPayDay('2017-11-03'));
        $this->assertTrue($schedule->isPayDay('2017-12-01'));
    }

    public function testIsPayDayFalse()
    {
        $schedule = new WeeklySchedule;
        $this->assertFalse($schedule->isPayDay('2017-01-05'));
        $this->assertFalse($schedule->isPayDay('2017-01-28'));
        $this->assertFalse($schedule->isPayDay('2017-02-13'));
        $this->assertFalse($schedule->isPayDay('2017-03-30'));
        $this->assertFalse($schedule->isPayDay('2017-04-29'));
        $this->assertFalse($schedule->isPayDay('2017-05-20'));
        $this->assertFalse($schedule->isPayDay('2017-06-01'));
        $this->assertFalse($schedule->isPayDay('2017-07-04'));
        $this->assertFalse($schedule->isPayDay('2017-08-05'));
        $this->assertFalse($schedule->isPayDay('2017-09-30'));
        $this->assertFalse($schedule->isPayDay('2017-10-21'));
        $this->assertFalse($schedule->isPayDay('2017-11-07'));
        $this->assertFalse($schedule->isPayDay('2017-12-11'));
    }
}