<?php

namespace Tests\Unit\Factory\Model;

use Payroll\Contract\TimeCard as TimeCardContract;
use Payroll\Factory\Model\TimeCard as TimeCardFactory;
use Payroll\Tests\TestCase;

class TimeCardTest extends TestCase
{
    public function testCreateTimeCard()
    {
        $data = [
            'date' => date('Y-m-d'),
            'hours' => 8
        ];

        $salesReceipt = TimeCardFactory::createTimeCard($data);
        $this->assertTrue($salesReceipt instanceof TimeCardContract);
        $this->assertEquals(date('Y-m-d'), $salesReceipt->getDate());
        $this->assertEquals(8, $salesReceipt->getHours());
    }
}