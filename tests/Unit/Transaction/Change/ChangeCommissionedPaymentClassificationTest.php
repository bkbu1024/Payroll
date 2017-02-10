<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Payroll\Factory\Model\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\Change\ChangeCommissionedPaymentClassification;

class ChangeCommissionedPaymentClassificationTest extends AbstractChangeEmployeeTestCase
{
    protected function change()
    {
        $salary = $this->faker->randomFloat(2, 800, 2200);
        $commissionRate = $this->faker->randomFloat(2, 10, 32);

        $constructorArgs = [
            $this->employee, $salary, $commissionRate
        ];

        $transaction = $this->getMockObject(ChangeCommissionedPaymentClassification::class, [
            'getPaymentClassification' => ['return' => new CommissionedClassification($salary, $commissionRate), 'times' => 'once'],
            'getPaymentSchedule' => ['return' => new BiweeklySchedule, 'times' => 'once'],
            'getType' => ['return' => Employee::COMMISSION, 'times' => 'once']
        ], $constructorArgs);

        $this->changedEmployee = $transaction->execute();
        $this->data['salary'] = $salary;
        $this->data['commissionRate'] = $commissionRate;
    }

    protected function setEmployee()
    {
        parent::setEmployee();
        $this->data['hourlyRate'] = $this->faker->randomFloat(2, 10, 30);
        $this->employee->setHourlyRate($this->data['hourlyRate']);
        $this->employee->setPaymentSchedule(new WeeklySchedule);
        $this->employee->setPaymentClassification(
            new HourlyClassification($this->data['hourlyRate']));
    }

    protected function assertTypeSpecificData()
    {
        /**
         * @var CommissionedClassification $paymentClassification
         */
        $paymentClassification = $this->changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof CommissionedClassification);
        $this->assertEquals($this->data['salary'], $paymentClassification->getSalary());
        $this->assertEquals($this->data['commissionRate'], $paymentClassification->getCommissionRate());

        /**
         * @var BiweeklySchedule
         */
        $paymentSchedule = $this->changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof BiweeklySchedule);

        $this->assertEquals(Employee::COMMISSION, $this->changedEmployee->getType());
    }
}
