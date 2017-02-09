<?php

namespace Payroll\Tests\Integration\Transaction\Add;

use Exception;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Add\AddSalariedEmployee;
use Payroll\Factory\Transaction\Add\TimeCard as AddTimeCardFactory;

class AddTimecardTest extends TestCase
{
    use DatabaseTransactions;

    public function testExecute()
    {
        $faker = Factory::create();
        /**
         * @var Employee
         */
        $employee = (new AddHourlyEmployee($faker->name, $faker->address, $faker->randomFloat(2, 10, 35)))->execute();

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
        $faker = Factory::create();
        /**
         * @var Employee
         */
        $employee = (new AddSalariedEmployee($faker->name, $faker->address, $faker->randomFloat(2, 1000, 3500)))->execute();

        try {
            $transaction = AddTimeCardFactory::create($employee, date('Y-m-d'), 8.0);
            $transaction->execute();
            $this->fail();
        } catch (Exception $ex) {
            $this->assertEquals('Tried to add time card to non-hourly employee', $ex->getMessage());
        }
    }
}
