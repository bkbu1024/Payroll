<?php

namespace Payroll\Tests\Unit\Transaction\Add;

use Payroll\Factory\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\Add\AddEmployee;
use Payroll\Transaction\Add\AddHourlyEmployee;

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
        $this->assertEquals($this->data['hourlyRate'], $this->employee->getHourlyRate());
        $this->assertEquals(Employee::HOURLY, $this->employee->getType());

        $this->assertTrue($this->employee->getPaymentSchedule() instanceof WeeklySchedule);
        $this->assertTrue($this->employee->getPaymentClassification() instanceof HourlyClassification);
    }
}
