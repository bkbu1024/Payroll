<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Faker\Factory;
use Payroll\Employee;
use Payroll\PaymentMethod\DirectMethod;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeDirectMethod;

class ChangeDirectMethodTest extends TestCase
{
    public function testExecute()
    {
        $faker = Factory::create();
        $address = $faker->address;

        $employee = (new AddHourlyEmployee(
            $faker->name,
            $address,
            $faker->randomFloat(2, 10, 30)))->execute();

        $bank = $faker->company;
        $account = $faker->bankAccountNumber;
        $transaction = new ChangeDirectMethod($employee, $bank, $account);
        /**
         * @var Employee
         */
        $changedEmployee = $transaction->execute();

        /**
         * @var DirectMethod
         */
        $paymentMethod = $changedEmployee->getPaymentMethod();
        $this->assertTrue($paymentMethod instanceof DirectMethod);
        $this->assertEquals($bank, $paymentMethod->getBank());
        $this->assertEquals($account, $paymentMethod->getAccount());
    }
}
