<?php

namespace Unit\Factory\Transaction;

use Payroll\Factory\Transaction\PayDay as PayDayFactory;
use Payroll\Tests\TestCase;
use Payroll\Transaction\PayDay;

class PayDayTest extends TestCase
{
    public function testCreatePayDay()
    {
        $payDay = PayDayFactory::createPayDay(date('Y-m-d'));
        $this->assertTrue($payDay instanceof PayDay);
    }
}