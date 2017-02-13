<?php

namespace Payroll\Tests\Integration\Transaction\Add;

use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Transaction\Add\AddEmployee as AddEmployee;

class AddSalariedEmployeeTest extends AbstractAddEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->data['salary'] = $this->faker->randomFloat(2, 1250, 3750);

        /**
         * @var AddEmployee $transaction
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->data['name'], 'address' => $this->data['address'],
            'salary' => $this->data['salary']
        ]);

        $this->employee = $transaction->execute();
    }

    protected function assertTypeSpecificData()
    {
        $this->assertEquals($this->data['salary'], $this->employee->getSalary());
        $this->assertEquals(EmployeeFactory::SALARIED, $this->employee->getPaymentClassification()->getType());

        $this->assertTrue($this->employee->getPaymentClassification() instanceof SalariedClassification);
        $this->assertTrue($this->employee->getPaymentSchedule() instanceof MonthlySchedule);
    }
}
