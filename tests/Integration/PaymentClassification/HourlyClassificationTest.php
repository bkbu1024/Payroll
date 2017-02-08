<?php

namespace Integration\PaymentClassification;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\Employee;
use Payroll\Paycheck;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Add\AddTimeCard;

class HourlyClassificationTest extends TestCase
{
    use DatabaseTransactions;

    public function testCalculatePay()
    {
        $faker = Factory::create();
        $hourlyRate = 15;

        /**
         * @var Employee $employee
         */
        $employee = (new AddHourlyEmployee($faker->name, $faker->address, $hourlyRate))->execute();
        (new AddTimeCard('2017-01-12', 10, $employee))->execute();
        (new AddTimeCard('2017-02-02', 5, $employee))->execute();
        (new AddTimeCard('2017-02-03', 8, $employee))->execute();
        (new AddTimeCard('2017-02-05', 8, $employee))->execute();

        $paycheck = new Paycheck([
            'date' => '2017-02-03'
        ]);

        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals($hourlyRate * 13, $netPay);
    }

    public function testCalculatePayWithOvertime()
    {
        $faker = Factory::create();
        $hourlyRate = 15;

        /**
         * @var Employee $employee
         */
        $employee = (new AddHourlyEmployee($faker->name, $faker->address, $hourlyRate))->execute();
        (new AddTimeCard('2017-01-12', 10, $employee))->execute();
        (new AddTimeCard('2017-02-02', 10, $employee))->execute();
        (new AddTimeCard('2017-02-03', 12, $employee))->execute();
        (new AddTimeCard('2017-02-05', 8, $employee))->execute();

        $paycheck = new Paycheck([
            'date' => '2017-02-03'
        ]);

        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals($hourlyRate * 16 + 1.5 * 6 * $hourlyRate, $netPay);
    }

    public function testCalculatePayNoTimeCards()
    {
        $faker = Factory::create();
        $hourlyRate = 15;

        /**
         * @var Employee $employee
         */
        $employee = (new AddHourlyEmployee($faker->name, $faker->address, $hourlyRate))->execute();
        (new AddTimeCard('2017-01-02', 10, $employee))->execute();
        (new AddTimeCard('2017-01-03', 12, $employee))->execute();
        (new AddTimeCard('2017-01-12', 10, $employee))->execute();
        (new AddTimeCard('2017-02-05', 8, $employee))->execute();

        $paycheck = new Paycheck([
            'date' => '2017-02-03'
        ]);

        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals(0, $netPay);
    }
}