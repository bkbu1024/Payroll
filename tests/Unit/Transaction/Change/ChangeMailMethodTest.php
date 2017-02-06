<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Faker\Factory;
use Payroll\Employee;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\PaymentMethod\MailMethod;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Change\ChangeMailMethod;

class ChangeMailMethodTest extends AbstractChangeEmployeeTestCase
{
    protected function assertTypeSpecificData()
    {
        /**
         * @var MailMethod
         */
        $paymentMethod = $this->changedEmployee->getPaymentMethod();
        $this->assertTrue($paymentMethod instanceof MailMethod);
        $this->assertEquals($this->data['address'], $paymentMethod->getAddress());
    }

    protected function change()
    {
        $this->data['address'] = $this->faker->address;
        $transaction = new ChangeMailMethod($this->employee, $this->data['address']);
        /**
         * @var Employee
         */
        $this->changedEmployee = $transaction->execute();
    }
}
