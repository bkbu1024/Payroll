<?php

namespace Integration\PaymentClassification;

use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Payroll\Contract\Employee;
use Payroll\Paycheck;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Factory\Transaction\Add\TimeCard as AddTimeCardFactory;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class HourlyClassificationTest extends TestCase
{
    use DatabaseTransactions;

    public function testCalculatePay()
    {
        $hourlyRate = 15;

        /**
         * @var Employee $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => $hourlyRate
        ]);

        $employee = $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-01-12', 10);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-02', 5);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-03', 8);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-05', 8);
        $transaction->execute();

        $paycheck = new Paycheck([
            'date' => '2017-02-03'
        ]);

        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals($hourlyRate * 13, $netPay);
    }

    public function testCalculatePayWithOvertime()
    {
        $hourlyRate = 15;

        /**
         * @var Employee $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => $hourlyRate
        ]);

        $employee = $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-01-12', 10);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-02', 10);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-03', 12);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-05', 8);
        $transaction->execute();

        $paycheck = new Paycheck([
            'date' => '2017-02-03'
        ]);

        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals($hourlyRate * 16 + 1.5 * 6 * $hourlyRate, $netPay);
    }

    public function testCalculatePayNoTimeCards()
    {
        $faker = Factory::create();
        $hourlyRate = 15;

        /**
         * @var Employee $employee
         */
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => $hourlyRate
        ]);

        $employee = $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-01-02', 10);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-01-03', 12);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-01-12', 10);
        $transaction->execute();

        $transaction = AddTimeCardFactory::create($employee, '2017-02-05', 8);
        $transaction->execute();

        $paycheck = new Paycheck([
            'date' => '2017-02-03'
        ]);

        $netPay = $employee->getPaymentClassification()->calculatePay($paycheck);
        $this->assertEquals(0, $netPay);
    }
}