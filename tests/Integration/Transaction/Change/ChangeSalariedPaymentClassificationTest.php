<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Faker\Factory;
use Payroll\Contract\Employee;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddEmployee;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeSalariedPaymentClassification;

class ChangeSalariedPaymentClassificationTest extends TestCase
{
    public function testExecute()
    {
        $faker = Factory::create();

        $name = $faker->name;
        $address = $faker->address;
        $hourlyRate = $faker->randomFloat(2, 12, 35);

        $employee = (new AddHourlyEmployee(
            $name,
            $address,
            $hourlyRate))->execute();

        $salary = $faker->randomFloat(2, 1200, 3400);
        $transaction = new ChangeSalariedPaymentClassification($employee, $salary);
        /**
         * @var Employee
         */
        $changedEmployee = $transaction->execute();

        /**
         * @var SalariedClassification $paymentClassification
         */
        $paymentClassification = $changedEmployee->getPaymentClassification();
        $this->assertTrue($paymentClassification instanceof SalariedClassification);
        $this->assertEquals($salary, $paymentClassification->getSalary());

        /**
         * @var WeeklySchedule $paymentSchedule
         */
        $paymentSchedule = $changedEmployee->getPaymentSchedule();
        $this->assertTrue($paymentSchedule instanceof MonthlySchedule);
        $this->assertEquals(AddEmployee::SALARIED, $changedEmployee->getType());
    }
}
