<?php

namespace Payroll\Tests\Unit\Transaction\Add;

use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\Transaction\Add\AddSalariedEmployee;

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
        $this->assertEquals($this->data['salary'], $this->employee->getSalary());
        $this->assertEquals(EmployeeFactory::SALARIED, $this->employee->getType());

        $this->assertTrue($this->employee->getPaymentClassification() instanceof SalariedClassification);
        $this->assertTrue($this->employee->getPaymentSchedule() instanceof MonthlySchedule);
    }
}
