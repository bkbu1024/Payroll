<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Payroll\Employee;
use Payroll\PaymentClassification\SalariedClassification;
use Payroll\PaymentMethod\MailMethod;
use Payroll\Transaction\Change\ChangeMailMethod;

class ChangeMailMethodTest extends AbstractChangeEmployeeTestCase
{
    protected function assertTypeSpecificData()
    {
        /**
         * @var MailMethod $paymentMethod
         */
        $paymentMethod = $this->changedEmployee->getPaymentMethod();
        $this->assertTrue($paymentMethod instanceof MailMethod);
        $this->assertEquals($this->data['address'], $paymentMethod->getAddress());
    }

    protected function change()
    {
        $this->data['address'] = $this->faker->address;
        $transaction = $this->getMockObject(ChangeMailMethod::class, [
            'getPaymentMethod' => ['return' => new MailMethod($this->data['address']), 'times' => 'once']
        ], [$this->employee, $this->data['address']]);

        /**
         * @var Employee
         */
        $this->changedEmployee = $transaction->execute();
    }

    protected function setEmployee()
    {
        parent::setEmployee();
        $this->employee->setPaymentClassification(
            new SalariedClassification($this->faker->randomFloat(2, 1200, 3500)));
    }
}
