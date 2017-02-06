<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Faker\Factory;
use Payroll\Contract\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddCommissionedEmployee;
use Payroll\Transaction\Add\AddEmployee;
use Payroll\Transaction\Change\ChangeHourlyPaymentClassification;

class ChangeHourlyPaymentClassificationTest extends TestCase
{
    public function testExecute()
    {
        $faker = Factory::create();
        $employee = (new AddCommissionedEmployee(
            $faker->name,
            $faker->address,
            $faker->randomFloat(2, 700, 2500),
            $faker->randomFloat(2, 10, 30)))->execute();

        $hourlyRate = $faker->randomFloat(2, 10, 33);
        $transaction = new ChangeHourlyPaymentClassification($employee, $hourlyRate);
        /**
         * @var Employee $changedEmployee
         */
        $changedEmployee = $transaction->execute();

        /**
         * @var HourlyClassification $paymentClassification
         */
        $paymentClassification = $changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof HourlyClassification);
        $this->assertEquals($hourlyRate, $paymentClassification->getHourlyRate());

        /**
         * @var WeeklySchedule $paymentSchedule
         */
        $paymentSchedule = $changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof WeeklySchedule);

        $this->assertEquals(AddEmployee::HOURLY, $changedEmployee->getType());
    }
}
