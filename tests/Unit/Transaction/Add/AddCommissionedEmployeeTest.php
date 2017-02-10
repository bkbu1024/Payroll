<?php

namespace Payroll\Tests\Unit\Transaction\Add;

use Payroll\Factory\Model\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Transaction\Add\AddCommissionedEmployee;

class AddCommissionedEmployeeTest extends AbstractAddEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->data['salary'] = $this->faker->randomFloat(2, 750, 2250);
        $this->data['commissionRate'] = $this->faker->randomFloat(2, 2, 15);

        // AddCommissionedEmployee
        $constructorArgs = [
            $this->data['name'],
            $this->data['address'],
            $this->data['salary'],
            $this->data['commissionRate']];

        $transaction = $this->getMockObject(AddCommissionedEmployee::class, [
            'getPaymentClassification' => [
                'return' => new CommissionedClassification($this->data['salary'], $this->data['commissionRate']),
                'times' => 'once'],
            'getPaymentSchedule' => [
                'return' => new BiweeklySchedule,
                'times' => 'once'
            ]
        ], $constructorArgs);

        $this->employee = $transaction->execute();
    }

    protected function assertTypeSpecificData()
    {
        $this->assertEquals($this->data['salary'], $this->employee->getSalary());
        $this->assertEquals($this->data['commissionRate'], $this->employee->getCommissionRate());
        $this->assertEquals(Employee::COMMISSION, $this->employee->getType());

        $this->assertTrue($this->employee->getPaymentSchedule() instanceof BiweeklySchedule);
        $this->assertTrue($this->employee->getPaymentClassification() instanceof CommissionedClassification);
    }
}
