<?php

namespace Payroll\Tests\Unit\Transaction\Add;

use Faker\Factory;
use Payroll\Employee;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeSalariedPaymentClassification;

class ChangeSalariedPaymentClassificationTest extends TestCase
{
    public function testExecute()
    {
        $faker = Factory::create();
        $employee = (new AddHourlyEmployee(
            $faker->name,
            $faker->address,
            $faker->randomFloat(2, 10, 30)))->execute();

        $salary = $faker->randomFloat(2, 1200, 3400);
        $transaction = new ChangeSalariedPaymentClassification($employee, $salary);
        /**
         * @var Employee
         */
        $changedEmployee = $transaction->execute();

        /**
         * @var SalariedClassification
         */
        $paymentClassification = $changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof SalariedClassification);
        $this->assertEquals($salary, $paymentClassification->getSalary());

        /**
         * @var MonthlySchedule
         */
        $paymentSchedule = $changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof MonthlySchedule);
    }
}
