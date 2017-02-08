<?php

namespace Integration\PaymentClassification;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\Employee;
use Payroll\Paycheck;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddCommissionedEmployee;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Add\AddSalesReceipt;
use Payroll\Transaction\Add\AddTimeCard;

class CommissionedClassificationTest extends TestCase
{
    use DatabaseTransactions;

    public function testCalculatePay()
    {
        $faker = Factory::create();
        $salary = 1200;

        /**
         * @var Employee $employee
         */
        $employee = (new AddCommissionedEmployee($faker->name, $faker->address, $salary, 10))->execute();
        (new AddSalesReceipt('2017-01-01', 1000, $employee))->execute();
        (new AddSalesReceipt('2017-01-06', 1000, $employee))->execute();
        (new AddSalesReceipt('2017-01-12', 1000, $employee))->execute();
        (new AddSalesReceipt('2017-01-13', 1000, $employee))->execute();
        (new AddSalesReceipt('2017-01-14', 1000, $employee))->execute();

        $paycheck = new Paycheck([
            'date' => '2017-01-13'
        ]);

        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals($salary + 400, $netPay);
    }

    public function testCalculatePayNoSalesReceipt()
    {
        $faker = Factory::create();
        $salary = 1100;

        /**
         * @var Employee $employee
         */
        $employee = (new AddCommissionedEmployee($faker->name, $faker->address, $salary, 10))->execute();
        (new AddSalesReceipt('2017-01-02', 1000, $employee))->execute();
        (new AddSalesReceipt('2017-01-03', 1200, $employee))->execute();
        (new AddSalesReceipt('2017-01-12', 900, $employee))->execute();
        (new AddSalesReceipt('2017-02-13', 850, $employee))->execute();

        $paycheck = new Paycheck([
            'date' => '2017-02-10'
        ]);

        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals($salary, $netPay);
    }
}