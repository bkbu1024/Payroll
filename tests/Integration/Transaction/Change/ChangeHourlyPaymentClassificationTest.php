<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Payroll\Factory\Model\Employee;
use Payroll\Factory\Transaction\Change\PaymentClassification as PaymentClassificationFactory;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class ChangeHourlyPaymentClassificationTest extends AbstractChangeEmployeeTestCase
{
    protected function setEmployee()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => $this->faker->randomFloat(2, 700, 2500),
            'commissionRate' => $this->faker->randomFloat(2, 10, 30)]);

        $this->employee = $transaction->execute();

        $this->data['hourlyRate'] = $this->faker->randomFloat(2, 10, 33);
        $transaction = PaymentClassificationFactory::create($this->employee, $this->data);
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
