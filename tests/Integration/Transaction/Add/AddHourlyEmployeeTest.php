<?php

namespace Payroll\Tests\Integration\Transaction\Add;

use Payroll\Factory\Model\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;
use Payroll\Transaction\Add\AddEmployee as AddEmployee;

class AddHourlyEmployeeTest extends AbstractAddEmployeeTestCase
{
    protected function setEmployee()
    {
        $this->data['hourlyRate'] = $this->faker->randomFloat(2, 12, 30);

        /**
         * @var AddEmployee $transaction
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->data['name'], 'address' => $this->data['address'],
            'hourlyRate' => $this->data['hourlyRate']
        ]);

        $this->employee = $transaction->execute();
    }

    protected function assertTypeSpecificData()
    {
        $this->assertEquals($this->data['hourlyRate'], $this->employee->getHourlyRate());
        $this->assertEquals(Employee::HOURLY, $this->employee->getPaymentClassification()->getType());

        $this->assertTrue($this->employee->getPaymentSchedule() instanceof WeeklySchedule);
        $this->assertTrue($this->employee->getPaymentClassification() instanceof HourlyClassification);
    }
}
