<?php

namespace Payroll\Integration\Unit\Transaction\Change;

use Faker\Factory;
use Payroll\Employee;
use Payroll\Factory\Transaction\Change\PaymentMethod;
use Payroll\PaymentMethod\MailMethod;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Change\ChangeMailMethod;

class ChangeMailMethodTest extends TestCase
{
    public function testExecute()
    {
        $faker = Factory::create();
        $address = $faker->address;

        $employee = (new AddHourlyEmployee(
            $faker->name,
            $address,
            $faker->randomFloat(2, 10, 30)))->execute();

        $transaction = PaymentMethod::create($employee, compact('address'));

        /**
         * @var Employee
         */
        $changedEmployee = $transaction->execute();

        /**
         * @var MailMethod
         */
        $paymentMethod = $changedEmployee->getPaymentMethod();
        $this->assertTrue($paymentMethod instanceof MailMethod);
        $this->assertEquals($address, $paymentMethod->getAddress());
    }
}
