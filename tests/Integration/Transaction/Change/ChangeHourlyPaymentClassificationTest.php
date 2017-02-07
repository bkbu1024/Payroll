<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Payroll\Factory\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\Add\AddCommissionedEmployee;
use Payroll\Transaction\Add\AddEmployee;
use Payroll\Transaction\Change\ChangeHourlyPaymentClassification;

class ChangeHourlyPaymentClassificationTest extends AbstractChangeEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->employee = (new AddCommissionedEmployee(
            $this->faker->name,
            $this->faker->address,
            $this->faker->randomFloat(2, 700, 2500),
            $this->faker->randomFloat(2, 10, 30)))->execute();

        $this->data['hourlyRate'] = $this->faker->randomFloat(2, 10, 33);
        $transaction = new ChangeHourlyPaymentClassification($this->employee, $this->data['hourlyRate']);
        $this->changedEmployee = $transaction->execute();
    }

    protected function change()
    {
        /**
         * @var HourlyClassification
         */
        $paymentClassification = $this->changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof HourlyClassification);
        $this->assertEquals($this->data['hourlyRate'], $paymentClassification->getHourlyRate());
    }

    protected function assertTypeSpecificData()
    {
        /**
         * @var WeeklySchedule
         */
        $paymentSchedule = $this->changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof WeeklySchedule);

        $this->assertEquals(Employee::HOURLY, $this->changedEmployee->getType());
    }
}
