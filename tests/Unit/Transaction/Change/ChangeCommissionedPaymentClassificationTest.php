<?php

namespace Payroll\Tests\Unit\Transaction\Add;

use Faker\Factory;
use Payroll\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeCommissionedPaymentClassification;

class ChangeCommissionedPaymentClassificationTest extends TestCase
{
    public function testExecute()
    {
        $faker = Factory::create();
        $employee = (new AddHourlyEmployee(
            $faker->name,
            $faker->address,
            $faker->randomFloat(2, 10, 30)))->execute();

        $salary = $faker->randomFloat(2, 800, 2200);
        $commissionRate = $faker->randomFloat(2, 10, 32);
        $transaction = new ChangeCommissionedPaymentClassification($employee, $salary, $commissionRate);
        /**
         * @var Employee
         */
        $changedEmployee = $transaction->execute();

        /**
         * @var CommissionedClassification
         */
        $paymentClassification = $changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof CommissionedClassification);
        $this->assertEquals($salary, $paymentClassification->getSalary());
        $this->assertEquals($commissionRate, $paymentClassification->getCommissionRate());

        /**
         * @var BiweeklySchedule
         */
        $paymentSchedule = $changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof BiweeklySchedule);
    }
}
