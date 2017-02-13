<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\Model\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\Change\ChangeSalariedPaymentClassification;

class ChangeSalariedPaymentClassificationTest extends AbstractChangeEmployeeTestCase
{
    protected function change()
    {
        $salary = $this->faker->randomFloat(2, 1000, 3000);
        $constructorArgs = [
            $this->employee, $salary
        ];

        $transaction = $this->getMockObject(ChangeSalariedPaymentClassification::class, [
            'getPaymentClassification' => ['return' => new SalariedClassification($salary), 'times' => 'once'],
            'getPaymentSchedule' => ['return' => new MonthlySchedule, 'times' => 'once'],
        ], $constructorArgs);

        $this->changedEmployee = $transaction->execute();
        $this->data['salary'] = $salary;
    }

    protected function setEmployee()
    {
        parent::setEmployee();
        $this->data['hourlyRate'] = $this->faker->randomFloat(2, 10, 30);
        $this->employee->setHourlyRate($this->data['hourlyRate']);

        $this->employee->setPaymentClassification(new HourlyClassification($this->data['hourlyRate']));
        $this->employee->setPaymentSchedule(new WeeklySchedule);
    }

    protected function assertTypeSpecificData()
    {
        /**
         * @var SalariedClassification $paymentClassification
         */
        $paymentClassification = $this->changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof SalariedClassification);
        $this->assertEquals($this->data['salary'], $paymentClassification->getSalary());

        /**
         * @var MonthlySchedule
         */
        $paymentSchedule = $this->changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof MonthlySchedule);

        $this->assertEquals(EmployeeFactory::SALARIED, $this->changedEmployee->getPaymentClassification()->getType());
    }
}
