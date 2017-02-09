<?php

namespace Payroll\Tests\Unit\Transaction\Add;

use Payroll\Factory\Model\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\Transaction\Add\AddCommissionedEmployee;
use Payroll\Transaction\Add\AddEmployee;

class AddCommissionedEmployeeTest extends AbstractAddEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->data['salary'] = $this->faker->randomFloat(2, 750, 2250);
        $this->data['commissionRate'] = $this->faker->randomFloat(2, 2, 15);

        $transaction = new AddCommissionedEmployee(
            $this->data['name'],
            $this->data['address'],
            $this->data['salary'],
            $this->data['commissionRate']);

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
