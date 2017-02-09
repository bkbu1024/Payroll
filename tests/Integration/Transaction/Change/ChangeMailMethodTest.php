<?php

namespace Payroll\Integration\Unit\Transaction\Change;

use Payroll\Employee;
use Payroll\Factory\Transaction\Change\PaymentMethod;
use Payroll\PaymentMethod\MailMethod;
use Payroll\Tests\TestCase;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class ChangeMailMethodTest extends TestCase
{
    public function testExecute()
    {
        $address = $this->faker->address;
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $address,
            'hourlyRate' => 15
        ]);

        $employee = $transaction->execute();
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
