<?php

namespace Payroll\Transaction\Change;

use Payroll\PaymentClassification\PaymentClassification;
use Payroll\PaymentSchedule\PaymentSchedule;

abstract class ChangePaymentClassification extends ChangeEmployee
{
    /**
     * @return PaymentClassification
     */
    abstract protected function getPaymentClassification();

    /**
     * @return PaymentSchedule
     */
    abstract protected function getPaymentSchedule();

    /**
     * @return string
     */
    abstract protected function getType();

    /**
     * @return \Payroll\Employee
     */
    protected function change()
    {
        $this->employee->setPaymentClassification($this->getPaymentClassification());
        $this->employee->setPaymentSchedule($this->getPaymentSchedule());
        $this->employee->save();

        return $this->employee;
    }
}