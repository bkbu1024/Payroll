<?php

namespace Payroll\Tests\Integration\Transaction\Add;

use Exception;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\Tests\TestCase;
use Payroll\Factory\Transaction\Add\TimeCard as AddTimeCardFactory;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class AddTimecardTest extends TestCase
{
    use DatabaseTransactions;

    public function testExecute()
    {
        /**
         * @var Employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 15
        ]);

        $employee = $transaction->execute();
        $transaction = AddTimeCardFactory::create($employee, date('Y-m-d'), 8.0);
        $transaction->execute();

        /**
         * @var HourlyClassification $paymentClassification
         */
        $paymentClassification = $employee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof HourlyClassification);

        $timeCard = $paymentClassification->getTimeCard((new \DateTime())->format('Y-m-d'));
        $this->assertEquals(8.0, $timeCard->hours);
        $this->assertEquals($employee->getId(), $timeCard->employee_id);
    }

    public function testExecuteInvalidUser()
    {
        /**
         * @var Employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'salary' => 1500
        ]);

        $employee = $transaction->execute();

        try {
            $transaction = AddTimeCardFactory::create($employee, date('Y-m-d'), 8.0);
            $transaction->execute();
            $this->fail();
        } catch (Exception $ex) {
            $this->assertEquals('Tried to add time card to non-hourly employee', $ex->getMessage());
        }
    }
}
