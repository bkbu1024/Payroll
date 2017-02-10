<?php

namespace Tests\Unit\Factory\Model;

use Payroll\Contract\Paycheck as PaycheckContract;
use Payroll\Factory\Model\Paycheck as PaycheckFactory;
use Payroll\Tests\TestCase;

class PaycheckTest extends TestCase
{
    public function testCreatePaycheckWithDate()
    {
        $paycheck = PaycheckFactory::create(date('Y-m-d'));
        $this->assertTrue($paycheck instanceof PaycheckContract);
        $this->assertEquals(date('Y-m-d'), $paycheck->getDate());
    }

    public function testCreatePaycheckWithoutDate()
    {
        $paycheck = PaycheckFactory::create();
        $this->assertTrue($paycheck instanceof PaycheckContract);
        $this->assertNull($paycheck->getDate());
    }
}