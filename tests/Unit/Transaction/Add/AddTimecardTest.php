<?php

namespace Payroll\Tests\Unit\Transaction;

use Faker\Factory;
use Payroll\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\Tests\TestCase;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Transaction\Add\AddTimeCard;

class AddTimeCardTest extends TestCase
{
    /**
     * @var Generator
     */
    protected $faker;

    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function testExecute()
    {
        $employee = factory(Employee::class)->create(['type' => EmployeeFactory::HOURLY]);
        $transaction = new AddTimeCard((new \DateTime())->format('Y-m-d'), 8.0, $employee);
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
        $employee = factory(Employee::class)->create(['type' => EmployeeFactory::SALARIED]);
        $transaction = new AddTimeCard((new \DateTime())->format('Y-m-d'), 8.0, $employee);

        try {
            $transaction->execute();
            $this->fail();
        } catch (\Exception $ex) {
            $this->assertEquals('Tried to add time card to non-hourly employee', $ex->getMessage());
        }
    }
}