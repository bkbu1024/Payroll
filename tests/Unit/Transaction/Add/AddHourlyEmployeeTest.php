<?php

namespace Payroll\Tests\Unit\Transaction\Add;

use Payroll\Factory\Model\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\Add\AddHourlyEmployee;

class AddHourlyEmployeeTest extends AbstractAddEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->data['hourlyRate'] = $this->faker->randomFloat(2, 12, 30);

        $constructorArgs = [
            $this->data['name'],
            $this->data['address'],
            $this->data['hourlyRate']];

        $transaction = $this->getMockObject(AddHourlyEmployee::class, [
            'getPaymentClassification' => [
                'return' => new HourlyClassification($this->data['hourlyRate']),
                'times' => 'once'],
            'getPaymentSchedule' => [
                'return' => new WeeklySchedule,
                'times' => 'once'
            ]
        ], $constructorArgs);

        $this->employee = $transaction->execute();
        $this->setPaymentMethod();
    }

    protected function assertTypeSpecificData()
    {
        $this->assertEquals($this->data['hourlyRate'], $this->employee->getHourlyRate());
        $this->assertEquals(Employee::HOURLY, $this->employee->getPaymentClassification()->getType());

        $this->assertTrue($this->employee->getPaymentSchedule() instanceof WeeklySchedule);
        $this->assertTrue($this->employee->getPaymentClassification() instanceof HourlyClassification);
    }
}
