<?php

namespace Payroll\Tests\Unit\Transaction\Add;

use Faker\Factory;
use Payroll\Employee;
use Payroll\PaymentClassification\CommissionedClassification;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddCommissionedEmployee;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeCommissionedPaymentClassification;
use Payroll\Transaction\Change\ChangeHourlyPaymentClassification;
use Payroll\Transaction\Change\ChangeSalariedPaymentClassification;

class ChangeCommissionedPaymentClassificationTest extends TestCase
{
    /**
     * @covers ChangeHourlyEmployee::execute()
     */
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
         * @var Employee $changedEmployee
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
    }
}
