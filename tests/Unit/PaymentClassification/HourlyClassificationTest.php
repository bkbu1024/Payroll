<?php

namespace Unit\PaymentClassification;

use Faker\Factory;
use Payroll\Employee;
use Payroll\Factory\Employee as EmployeeFactory;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\Tests\TestCase;
use Payroll\TimeCard;

class HourlyClassificationTest extends TestCase
{
    public function testCalculatePayForTimeCard()
    {
        $faker = Factory::create();
        /**
         * @var Employee $employee
         */
        $employee = factory(Employee::class)->create([
            'name'  => $faker->name,
            'address' => $faker->address,
            'hourly_rate' => 15,
            'type' => EmployeeFactory::HOURLY
        ]);

        /**
         * @var TimeCard $timeCard
         */
        $timeCard = factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => date('Y-m-d'),
            'hours' => 5
        ]);

        $classification = new HourlyClassification($employee->getHourlyRate());
        $netPay = $this->invokeMethod($classification, 'calculatePayForTimeCard', [$timeCard]);

        $this->assertEquals(75, $netPay);

        $timeCard->setHours(7.5);
        $netPay = $this->invokeMethod($classification, 'calculatePayForTimeCard', [$timeCard]);
        $this->assertEquals(112.5, $netPay);
    }

    public function testCalculatePayForTimeCardWithOvertime()
    {
        $faker = Factory::create();
        /**
         * @var Employee $employee
         */
        $employee = factory(Employee::class)->create([
            'name'  => $faker->name,
            'address' => $faker->address,
            'hourly_rate' => 15,
            'type' => EmployeeFactory::HOURLY
        ]);

        /**
         * @var TimeCard $timeCard
         */
        $timeCard = factory(TimeCard::class)->create([
            'employee_id' => $employee->getId(),
            'date' => date('Y-m-d'),
            'hours' => 10
        ]);

        $classification = new HourlyClassification($employee->getHourlyRate());
        $netPay = $this->invokeMethod($classification, 'calculatePayForTimeCard', [$timeCard]);

        $this->assertEquals(8 * 15 + (2 * (15 * 1.5)), $netPay);

        $timeCard->setHours(12);
        $netPay = $this->invokeMethod($classification, 'calculatePayForTimeCard', [$timeCard]);
        $this->assertEquals(8 * 15 + (4 * (15 * 1.5)), $netPay);
    }

    public function testIsInPayPeriodOk()
    {
        $classification = new HourlyClassification(15);

        // paydate: 02-03 in perios: 01-28 .. 02-03

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-28', '2017-02-03']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-29', '2017-02-03']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-30', '2017-02-03']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-31', '2017-02-03']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-01', '2017-02-03']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-02', '2017-02-03']);
        $this->assertTrue($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-03', '2017-02-03']);
        $this->assertTrue($isIn);
    }

    public function testIsInPayPeriodNotOk()
    {
        $classification = new HourlyClassification(15);

        // paydate: 02-03 in perios: 01-28 .. 02-03

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-12', '2017-02-03']);
        $this->assertFalse($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-01-27', '2017-02-03']);
        $this->assertFalse($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-04', '2017-02-03']);
        $this->assertFalse($isIn);

        $isIn = $this->invokeMethod($classification, 'isInPayPeriod', ['2017-02-14', '2017-02-03']);
        $this->assertFalse($isIn);
    }
}