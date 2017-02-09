<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeSalariedPaymentClassification;

class ChangeSalariedPaymentClassificationTest extends AbstractChangeEmployeeTestCase
{
    protected function setEmployee()
    {
        $name = $this->faker->name;
        $address = $this->faker->address;
        $hourlyRate = $this->faker->randomFloat(2, 12, 35);

        $this->employee = (new AddHourlyEmployee(
            $name,
            $address,
            $hourlyRate))->execute();
    }

    protected function change()
    {
        $this->data['salary'] = $this->faker->randomFloat(2, 1200, 3400);
        $transaction = new ChangeSalariedPaymentClassification($this->employee, $this->data['salary']);
        $this->changedEmployee = $transaction->execute();
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
         * @var WeeklySchedule $paymentSchedule
         */
        $paymentSchedule = $this->changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof MonthlySchedule);
        $this->assertEquals(EmployeeFactory::SALARIED, $this->changedEmployee->getType());
    }


}
