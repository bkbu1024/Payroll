<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Payroll\Factory\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\Add\AddEmployee;
use Payroll\Transaction\Change\ChangeHourlyPaymentClassification;

class ChangeHourlyPaymentClassificationTest extends AbstractChangeEmployeeTestCase
{
    protected function change()
    {
        $hourlyRate = $this->faker->randomFloat(2, 10, 30);
        $transaction = new ChangeHourlyPaymentClassification($this->employee, $hourlyRate);

        $this->changedEmployee = $transaction->execute();
        $this->data['hourlyRate'] = $hourlyRate;
    }

    protected function setEmployee()
    {
        parent::setEmployee();
        $this->data['salary'] = $this->faker->randomFloat(2, 1200, 3000);
        $this->employee->setSalary($this->data['salary']);

        $this->employee->setPaymentClassification(new SalariedClassification($this->data['salary']));
        $this->employee->setPaymentSchedule(new MonthlySchedule());
    }

    protected function assertTypeSpecificData()
    {
        /**
         * @var HourlyClassification $paymentClassification
         */
        $paymentClassification = $this->changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof HourlyClassification);
        $this->assertEquals($this->data['hourlyRate'], $paymentClassification->getHourlyRate());

        /**
         * @var WeeklySchedule
         */
        $paymentSchedule = $this->changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof WeeklySchedule);

        $this->assertEquals(Employee::HOURLY, $this->changedEmployee->getType());
    }
}
