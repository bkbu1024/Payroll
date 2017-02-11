<?php

namespace Unit\Factory\Transaction\Change;

use Faker\Factory;
use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Employee;
use Payroll\Factory\Model\Employee as EmployeeFactory;
use Payroll\Factory\Transaction\Change\PaymentClassification;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Change\ChangeCommissionedPaymentClassification;
use Payroll\Transaction\Change\ChangeHourlyPaymentClassification;
use Payroll\Transaction\Change\ChangeSalariedPaymentClassification;

class PaymentClassificationTest extends TestCase
{
    public function __construct()
    {
        $this->faker = Factory::create();
    }

    public function testCreateSalaried()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = factory(Employee::class)->create([
            'type' => EmployeeFactory::SALARIED
        ]);

        $transaction = PaymentClassification::create($employee, [
           'salary' => 2500
        ]);

        $this->assertTrue($transaction instanceof ChangeSalariedPaymentClassification);
    }

    public function testCreateCommissioned()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = factory(Employee::class)->create([
            'type' => EmployeeFactory::COMMISSION
        ]);

        $transaction = PaymentClassification::create($employee, [
            'salary' => 2500,
            'commissionRate' => 10
        ]);

        $this->assertTrue($transaction instanceof ChangeCommissionedPaymentClassification);
    }

    public function testCreateHourly()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = factory(Employee::class)->create([
            'type' => EmployeeFactory::HOURLY
        ]);

        $transaction = PaymentClassification::create($employee, [
            'hourlyRate' => 15
        ]);

        $this->assertTrue($transaction instanceof ChangeHourlyPaymentClassification);
    }

    public function testCreateInvalidData()
    {
        /**
         * @var EmployeeContract $employee
         */
        $employee = factory(Employee::class)->create([
            'type' => EmployeeFactory::COMMISSION
        ]);

        try {
            PaymentClassification::create($employee, [
                'invalid' => 2500,
            ]);
            $this->fail();
        } catch (\Exception $ex) {
            $this->assertEquals('Never should reach here', $ex->getMessage());
        }
    }
}