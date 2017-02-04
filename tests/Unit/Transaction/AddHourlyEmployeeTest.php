<?php

namespace Payroll\Tests\Unit\Transaction;

use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\AddEmployee;
use Payroll\Transaction\AddHourlyEmployee;

class AddHourlyEmployeeTest extends AbstractAddEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->data['hourlyRate'] = $this->faker->randomFloat(2, 12, 30);

        $transaction = new AddHourlyEmployee(
            $this->data['name'],
            $this->data['address'],
            $this->data['hourlyRate']);

        $this->employee = $transaction->execute();
    }

    protected function assertTypeSpecificData()
    {
        $this->assertEquals($this->data['hourlyRate'], $this->employee->hourly_rate);
        $this->assertEquals(AddEmployee::HOURLY, $this->employee->type);

        $this->assertTrue($this->employee->getPaymentSchedule() instanceof WeeklySchedule);
        $this->assertTrue($this->employee->getPaymentClassification() instanceof HourlyClassification);
    }
}
