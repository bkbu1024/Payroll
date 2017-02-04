<?php

namespace Payroll\Tests\Unit\Transaction;

use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\Transaction\AddCommissionedEmployee;
use Payroll\Transaction\AddEmployee;

class AddCommissionedEmployeeTest extends AbstractAddEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->data['salary'] = $this->faker->randomFloat(2, 750, 2250);
        $this->data['commission'] = $this->faker->randomFloat(2, 75, 250);

        $transaction = new AddCommissionedEmployee(
            $this->data['name'],
            $this->data['address'],
            $this->data['salary'],
            $this->data['commission']);

        $this->employee = $transaction->execute();
    }

    protected function assertTypeSpecificData()
    {
        $this->assertEquals($this->data['salary'], $this->employee->salary);
        $this->assertEquals($this->data['commission'], $this->employee->commission);
        $this->assertEquals(AddEmployee::COMMISSION, $this->employee->type);

        $this->assertTrue($this->employee->getPaymentSchedule() instanceof BiweeklySchedule);
        $this->assertTrue($this->employee->getPaymentClassification() instanceof CommissionedClassification);
    }
}
