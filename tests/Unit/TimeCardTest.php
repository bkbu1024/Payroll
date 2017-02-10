<?php

namespace Tests\Unit;

use Payroll\Contract\TimeCard as TimeCardContract;
use Payroll\Tests\TestCase;
use Payroll\TimeCard;

class TimeCardTest extends TestCase
{
    public function testIsInPayPeriodOk()
    {
        /*
         | Paydate = 02-03
         | Period = 01-28 .. 02-03
         */
        $payDate = '2017-02-03';

        /**
         * @var TimeCardContract $timeCard
         */
        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-01-28']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-01-29']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-01-30']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-01-31']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-02-01']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertTrue($isIn);

        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-02-02']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertTrue($isIn);
    }

    public function testIsInPayPeriodNotOk()
    {
        /*
         | Paydate = 02-03
         | Period = 01-28 .. 02-03
         */
        $payDate = '2017-02-03';

        /**
         * @var TimeCardContract $timeCard
         */
        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-01-12']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertFalse($isIn);

        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-01-27']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertFalse($isIn);

        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-02-04']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertFalse($isIn);

        $timeCard = $this->getMockObject(TimeCard::class, ['getDate' => ['return' => '2017-02-14']]);
        $isIn = $timeCard->isInPayPeriod($payDate);
        $this->assertFalse($isIn);
    }
}