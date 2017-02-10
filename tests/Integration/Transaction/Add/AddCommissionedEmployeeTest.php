<?php

namespace Payroll\Tests\Integration\Transaction\Add;

use Payroll\Factory\Model\Employee;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Transaction\Add\AddEmployee as AddEmployee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;

class AddCommissionedEmployeeTest extends AbstractAddEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->data['salary'] = $this->faker->randomFloat(2, 750, 2250);
        $this->data['commissionRate'] = $this->faker->randomFloat(2, 2, 15);

        /**
         * @var AddEmployee $transaction
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->data['name'], 'address' => $this->data['address'],
            'salary' => $this->data['salary'], 'commissionRate' => $this->data['commissionRate']
        ]);

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
