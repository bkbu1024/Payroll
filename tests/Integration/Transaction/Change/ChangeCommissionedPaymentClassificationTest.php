<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\Factory\Model\Employee as Employee1;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\Transaction\Add\AddEmployee;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeCommissionedPaymentClassification;

class ChangeCommissionedPaymentClassificationTest extends AbstractChangeEmployeeTestCase
{
    protected function setEmployee()
    {
        $name = $this->faker->name;
        $address = $this->faker->address;
        $hourlyRate = $this->faker->randomFloat(2, 10, 30);

        $this->employee = (new AddHourlyEmployee($name, $address, $hourlyRate))->execute();
    }

    protected function change()
    {
        $this->data['salary'] = $this->faker->randomFloat(2, 800, 2200);
        $this->data['commissionRate '] = $this->faker->randomFloat(2, 10, 32);
        $transaction = new ChangeCommissionedPaymentClassification($this->employee, $this->data['salary'], $this->data['commissionRate ']);

        /**
         * @var Employee
         */
        $this->changedEmployee = $transaction->execute();
    }

    protected function assertTypeSpecificData()
    {
        /**
         * @var CommissionedClassification $paymentClassification
         */
        $paymentClassification = $this->changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof CommissionedClassification);
        $this->assertEquals($this->data['salary'], $paymentClassification->getSalary());
        $this->assertEquals($this->data['commissionRate '], $paymentClassification->getCommissionRate());

        /**
         * @var BiweeklySchedule $paymentSchedule
         */
        $paymentSchedule = $this->changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof BiweeklySchedule);
        $this->assertEquals(Employee1::COMMISSION, $this->changedEmployee->getType());
    }
}
