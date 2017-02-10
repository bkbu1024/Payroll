<?php

namespace Tests\Unit;

use Payroll\Contract\SalesReceipt as SalesReceiptContract;
use Payroll\SalesReceipt;
use Payroll\Tests\TestCase;

class SalesReceiptTest extends TestCase
{
    public function testIsInPayPeriodOk()
    {
        $payDate = '2017-02-10';

        /*
         | Paydate = 02-10
         | In this period = 01-28 .. 02-10
         */

        /**
         * @var SalesReceiptContract $salesReceipt
         */
        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-01-28']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-01-29']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-01-30']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-01-31']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-01']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-02']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-03']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-04']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-05']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-06']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-07']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-08']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-09']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-10']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertTrue($isIn);
    }

    public function testIsInPayPeriodNotOk()
    {
        $payDate = '2017-02-10';

        /*
         | Paydate = 02-10
         | In this period = 01-28 .. 02-10
         */

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-01-12']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertFalse($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-01-27']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertFalse($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-11']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertFalse($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-12']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertFalse($isIn);

        $salesReceipt = $this->getMockObject(SalesReceipt::class, ['getDate' => ['return' => '2017-02-24']]);
        $isIn = $salesReceipt->isInPayPeriod($payDate);
        $this->assertFalse($isIn);
    }
}