<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Faker\Factory;
use Payroll\Employee;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\PaymentMethod\PaymentMethod;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Change\ChangeHoldMethod;

class ChangeHoldMethodTest extends AbstractChangeEmployeeTestCase
{
    protected function assertTypeSpecificData()
    {
        /**
         * @var PaymentMethod
         */
        $paymentMethod = $this->changedEmployee->getPaymentMethod();
        $this->assertTrue($paymentMethod instanceof HoldMethod);
    }

    protected function change()
    {
        $transaction = new ChangeHoldMethod($this->employee);
        $this->changedEmployee = $transaction->execute();
    }
}
