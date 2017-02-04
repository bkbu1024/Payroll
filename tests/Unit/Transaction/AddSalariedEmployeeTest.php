<?php

namespace Payroll\Tests\Unit\Transaction;

use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\Transaction\AddSalariedEmployee;
use Payroll\Transaction\AddEmployee;

class AddSalariedEmployeeTest extends AbstractAddEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->data['salary'] = $this->faker->randomFloat(2, 1250, 3750);

        $transaction = new AddSalariedEmployee(
            $this->data['name'],
            $this->data['address'],
            $this->data['salary']);

        $this->employee = $transaction->execute();
    }

    protected function assertTypeSpecificData()
    {
        $this->assertEquals($this->data['salary'], $this->employee->salary);
        $this->assertEquals(AddEmployee::SALARIED, $this->employee->type);

        $this->assertTrue($this->employee->getPaymentClassification() instanceof SalariedClassification);
        $this->assertTrue($this->employee->getPaymentSchedule() instanceof MonthlySchedule);
    }
}
