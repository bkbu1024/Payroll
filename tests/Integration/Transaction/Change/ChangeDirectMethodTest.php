<?php

namespace Payroll\Tests\Integration\Transaction\Change;

use Faker\Factory;
use Payroll\Employee;
use Payroll\Factory\Transaction\Change\PaymentMethod;
use Payroll\PaymentMethod\DirectMethod;
use Payroll\Tests\TestCase;
use Payroll\Factory\Transaction\Add\Employee as AddEmployeeFactory;

class ChangeDirectMethodTest extends TestCase
{
    public function testExecute()
    {
        $transaction = AddEmployeeFactory::create([
            'name' => $this->faker->name, 'address' => $this->faker->address,
            'hourlyRate' => 15
        ]);

        $employee = $transaction->execute();
        $bank = $this->faker->company;
        $account = $this->faker->bankAccountNumber;
        $transaction = PaymentMethod::create($employee, compact('bank', 'account'));

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
