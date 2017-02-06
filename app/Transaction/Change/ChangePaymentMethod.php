<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\PaymentMethod\PaymentMethod;

abstract class ChangePaymentMethod extends ChangeEmployee
{
    /**
     * @return PaymentMethod
     */
    abstract protected function getPaymentMethod();

    /**
     * @return Employee
     */
    protected function change()
    {
        $this->employee->setPaymentMethod($this->getPaymentMethod());

        return $this->employee;
    }
}