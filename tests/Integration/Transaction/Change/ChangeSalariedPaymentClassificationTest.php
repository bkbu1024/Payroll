<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\Transaction\Change\PaymentClassification as PaymentClassificationFactory;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class ChangeSalariedPaymentClassificationTest extends AbstractChangeEmployeeTestCase
{
    protected function setEmployee()
    {
        $name = $this->faker->name;
        $address = $this->faker->address;
        $hourlyRate = $this->faker->randomFloat(2, 12, 35);

        $transaction = AddEmployeeFactory::create([
            'name' => $name, 'address' => $address,
            'hourlyRate' => $hourlyRate
        ]);

        $this->employee = $transaction->execute();
    }

    protected function change()
    {
        $this->data['salary'] = $this->faker->randomFloat(2, 1200, 3400);
        $transaction = PaymentClassificationFactory::create($this->employee, $this->data);
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
        $this->assertEquals(EmployeeFactory::SALARIED, $this->changedEmployee->getPaymentClassification()->getType());
    }
}
