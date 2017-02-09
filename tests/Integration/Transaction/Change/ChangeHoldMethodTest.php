<?php

namespace Payroll\Integration\Unit\Transaction\Change;

use Faker\Factory;
use Payroll\Employee;
use Payroll\Factory\Transaction\Change\PaymentMethod as PaymentMethodFactory;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\PaymentMethod\PaymentMethod;
use Payroll\Tests\TestCase;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class ChangeHoldMethodTest extends TestCase
{
    public function testExecute()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 15
        ]);

        $employee = $transaction->execute();
        $transaction = PaymentMethodFactory::create($employee);
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
