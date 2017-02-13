<?php

namespace Payroll\Tests\Unit\Transaction\Change;

use Payroll\PaymentClassification\SalariedClassification;
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
        $constructorArgs = [
            $this->employee, $this->data['bank'], $this->data['account']
        ];

        $transaction = $this->getMockObject(ChangeDirectMethod::class, [
            'getPaymentMethod' => ['return' => new DirectMethod($this->data['bank'], $this->data['account']), 'times' => 'once']
        ], $constructorArgs);

        $this->changedEmployee = $transaction->execute();
    }

    protected function setEmployee()
    {
        parent::setEmployee();
        $this->employee->setPaymentClassification(
            new SalariedClassification($this->faker->randomFloat(2, 1200, 3500)));
    }
}
