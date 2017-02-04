<?php

namespace Payroll\Tests\Unit\Transaction;

use Payroll\Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Faker\Factory;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\Transaction\AddEmployee;
use Payroll\Transaction\AddHourlyEmployee;

class AddHourlyEmployeeTest extends TestCase
{
    /**
     * @covers AddHourlyEmployee::execute()
     */
    public function testExecute()
    {
        $faker = Factory::create();
        $name = $faker->name;
        $hourlyRate = $faker->randomFloat(2, 7.5, 32);

        $transaction = new AddHourlyEmployee(
            $name,
            $faker->address,
            $hourlyRate);

        $employee = $transaction->execute();
        $this->assertEquals($name, $employee->name);
        
        $this->assertTrue($employee->getPaymentClassification() instanceof HourlyClassification);
        $this->assertEquals($hourlyRate, $employee->hourly_rate);
        $this->assertEquals(AddEmployee::HOURLY, $employee->type);

        $this->assertTrue($employee->getPaymentSchedule() instanceof WeeklySchedule);
        $this->assertTrue($employee->getPaymentMethod() instanceof HoldMethod);
    }
}
