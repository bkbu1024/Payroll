<?php

namespace Payroll\Integration\Unit\Transaction\Change;

use Faker\Factory;
use Payroll\Employee;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\PaymentMethod\PaymentMethod;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeHoldMethod;

class ChangeHoldMethodTest extends TestCase
{
    public function testExecute()
    {
        $faker = Factory::create();
        $address = $faker->address;

        $employee = (new AddHourlyEmployee(
            $faker->name,
            $address,
            $faker->randomFloat(2, 10, 30)))->execute();

        $transaction = new ChangeHoldMethod($employee);
        /**
         * @var Employee
         */
        $changedEmployee = $transaction->execute();

        /**
         * @var PaymentMethod
         */
        $paymentMethod = $changedEmployee->getPaymentMethod();
        $this->assertTrue($paymentMethod instanceof HoldMethod);
    }
}
