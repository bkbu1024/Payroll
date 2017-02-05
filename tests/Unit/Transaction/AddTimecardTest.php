<?php

namespace Payroll\Tests\Unit\Transaction;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\Tests\TestCase;
use Payroll\Transaction\AddHourlyEmployee;
use Payroll\Transaction\AddTimeCard;

class AddTimecardTest extends TestCase
{
    use DatabaseTransactions;

    public function testExecute()
    {
        $faker = Factory::create();
        /**
         * @var Employee
         */
        $employee = (new AddHourlyEmployee($faker->name, $faker->address, $faker->randomFloat(2, 10, 35)))->execute();

        $transaction = new AddTimeCard((new \DateTime())->format('Y-m-d'), 8.0, $employee);
        $transaction->execute();

        /**
         * @var HourlyClassification $paymentClassification
         */
        $paymentClassification = $employee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof HourlyClassification);

        $timeCard = $paymentClassification->getTimeCard((new \DateTime())->format('Y-m-d'));
        $this->assertEquals(8.0, $timeCard->hours);
        $this->assertEquals($employee->id, $timeCard->employee_id);
    }
}
