<?php

namespace Unit\PaymentSchedule;

use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\Tests\TestCase;

class BiweeklyScheduleTest extends TestCase
{
    public function testIsPayDayTrue()
    {
        // Every second Friday
        $schedule = new BiweeklySchedule;
        $this->assertTrue($schedule->isPayDay('2017-01-13'));
        $this->assertTrue($schedule->isPayDay('2017-01-27'));

        $this->assertTrue($schedule->isPayDay('2017-02-10'));
        $this->assertTrue($schedule->isPayDay('2017-02-24'));

        $this->assertTrue($schedule->isPayDay('2017-03-10'));
        $this->assertTrue($schedule->isPayDay('2017-03-24'));

        $this->assertTrue($schedule->isPayDay('2017-04-14'));
        $this->assertTrue($schedule->isPayDay('2017-04-28'));

        $this->assertTrue($schedule->isPayDay('2017-05-12'));
        $this->assertTrue($schedule->isPayDay('2017-05-26'));

        $this->assertTrue($schedule->isPayDay('2017-06-09'));
        $this->assertTrue($schedule->isPayDay('2017-06-23'));

        $this->assertTrue($schedule->isPayDay('2017-07-14'));
        $this->assertTrue($schedule->isPayDay('2017-07-28'));

        $this->assertTrue($schedule->isPayDay('2017-08-11'));
        $this->assertTrue($schedule->isPayDay('2017-08-25'));

        $this->assertTrue($schedule->isPayDay('2017-09-08'));
        $this->assertTrue($schedule->isPayDay('2017-09-22'));

        $this->assertTrue($schedule->isPayDay('2017-10-13'));
        $this->assertTrue($schedule->isPayDay('2017-10-27'));

        $this->assertTrue($schedule->isPayDay('2017-11-10'));
        $this->assertTrue($schedule->isPayDay('2017-11-24'));

        $this->assertTrue($schedule->isPayDay('2017-12-08'));
        $this->assertTrue($schedule->isPayDay('2017-12-22'));
    }

    public function testIsPayDayFalse()
    {
        $schedule = new BiweeklySchedule;
        $this->assertFalse($schedule->isPayDay('2017-01-06'));
        $this->assertFalse($schedule->isPayDay('2017-01-20'));
        $this->assertFalse($schedule->isPayDay('2017-01-21'));

        $this->assertFalse($schedule->isPayDay('2017-02-03'));
        $this->assertFalse($schedule->isPayDay('2017-02-17'));
        $this->assertFalse($schedule->isPayDay('2017-02-08'));

        $this->assertFalse($schedule->isPayDay('2017-03-03'));
        $this->assertFalse($schedule->isPayDay('2017-03-17'));
        $this->assertFalse($schedule->isPayDay('2017-03-31'));
        $this->assertFalse($schedule->isPayDay('2017-03-27'));

        $this->assertFalse($schedule->isPayDay('2017-04-07'));
        $this->assertFalse($schedule->isPayDay('2017-04-21'));
        $this->assertFalse($schedule->isPayDay('2017-04-12'));

        $this->assertFalse($schedule->isPayDay('2017-05-05'));
        $this->assertFalse($schedule->isPayDay('2017-05-19'));
        $this->assertFalse($schedule->isPayDay('2017-05-20'));

        $this->assertFalse($schedule->isPayDay('2017-06-02'));
        $this->assertFalse($schedule->isPayDay('2017-06-16'));
        $this->assertFalse($schedule->isPayDay('2017-06-30'));
        $this->assertFalse($schedule->isPayDay('2017-06-24'));

        $this->assertFalse($schedule->isPayDay('2017-07-07'));
        $this->assertFalse($schedule->isPayDay('2017-07-21'));
        $this->assertFalse($schedule->isPayDay('2017-07-18'));

        $this->assertFalse($schedule->isPayDay('2017-08-04'));
        $this->assertFalse($schedule->isPayDay('2017-08-18'));
        $this->assertFalse($schedule->isPayDay('2017-08-01'));

        $this->assertFalse($schedule->isPayDay('2017-09-01'));
        $this->assertFalse($schedule->isPayDay('2017-09-15'));
        $this->assertFalse($schedule->isPayDay('2017-09-29'));

        $this->assertFalse($schedule->isPayDay('2017-10-06'));
        $this->assertFalse($schedule->isPayDay('2017-10-20'));
        $this->assertFalse($schedule->isPayDay('2017-10-10'));

        $this->assertFalse($schedule->isPayDay('2017-11-03'));
        $this->assertFalse($schedule->isPayDay('2017-11-17'));
        $this->assertFalse($schedule->isPayDay('2017-11-30'));

        $this->assertFalse($schedule->isPayDay('2017-12-01'));
        $this->assertFalse($schedule->isPayDay('2017-12-15'));
        $this->assertFalse($schedule->isPayDay('2017-12-29'));
        $this->assertFalse($schedule->isPayDay('2017-12-30'));
    }
}