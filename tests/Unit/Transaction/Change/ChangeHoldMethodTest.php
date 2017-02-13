<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\PaymentMethod\PaymentMethod;
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
        $transaction = $this->getMockObject(ChangeHoldMethod::class, [
            'getPaymentMethod' => ['return' => new HoldMethod, 'times' => 'once']
        ], [$this->employee]);

        $this->changedEmployee = $transaction->execute();
    }

    protected function setEmployee()
    {
        parent::setEmployee();
        $this->employee->setPaymentClassification(
            new SalariedClassification($this->faker->randomFloat(2, 1200, 3500)));
    }
}
