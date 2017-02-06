<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddEmployee;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeCommissionedPaymentClassification;

class ChangeCommissionedPaymentClassificationTest extends TestCase
{
    use DatabaseTransactions;

    public function testExecute()
    {
        $faker = Factory::create();

        $name = $faker->name;
        $address = $faker->address;
        $hourlyRate = $faker->randomFloat(2, 10, 30);

        $employee = (new AddHourlyEmployee($name, $address, $hourlyRate))->execute();

        $salary = $faker->randomFloat(2, 800, 2200);
        $commissionRate = $faker->randomFloat(2, 10, 32);
        $transaction = new ChangeCommissionedPaymentClassification($employee, $salary, $commissionRate);

        /**
         * @var Employee
         */
        $changedEmployee = $transaction->execute();

        /**
         * @var CommissionedClassification $paymentClassification
         */
        $paymentClassification = $changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof CommissionedClassification);
        $this->assertEquals($salary, $paymentClassification->getSalary());
        $this->assertEquals($commissionRate, $paymentClassification->getCommissionRate());

        /**
         * @var BiweeklySchedule $paymentSchedule
         */
        $paymentSchedule = $changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof BiweeklySchedule);
        $this->assertEquals(AddEmployee::COMMISSION, $changedEmployee->getType());
    }
}
