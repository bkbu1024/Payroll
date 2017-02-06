<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Payroll\PaymentMethod\DirectMethod;
use Payroll\Transaction\Change\ChangeDirectMethod;

class ChangeDirectMethodTest extends AbstractChangeEmployeeTestCase
{
    protected function assertTypeSpecificData()
    {
        /**
         * @var DirectMethod $paymentMethod
         */
        $paymentMethod = $this->changedEmployee->getPaymentMethod();
        $this->assertTrue($paymentMethod instanceof DirectMethod);
        $this->assertEquals($this->data['bank'], $paymentMethod->getBank());
        $this->assertEquals($this->data['account'], $paymentMethod->getAccount());
    }

    protected function change()
    {
        $this->data['bank'] = $this->faker->company;
        $this->data['account'] = $this->faker->bankAccountNumber;
        $transaction = new ChangeDirectMethod($this->employee, $this->data['bank'], $this->data['account']);
        $this->changedEmployee = $transaction->execute();
    }
}
