<?php

namespace Tests\Unit\PaymentMethod;

use Payroll\PaymentMethod\Factory;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\Tests\TestCase;

class FactoryTest extends TestCase
{
    /**
     * @covers Factory::createMethod()
     */
    public function testCreateMethodGivenType()
    {
        $method = Factory::createDefault();
        $this->assertTrue($method instanceof HoldMethod);
    }
}
