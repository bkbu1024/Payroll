<?php

namespace Payroll\PaymentClassification;

use Payroll\Employee;

abstract class PaymentClassification
{
    /**
     * @var Employee
     */
    protected $employee = null;

    /**
     * @return Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * @param Employee $employee
     */
    public function setEmployee($employee)
    {
        $this->employee = $employee;
    }

    /**
     * @return float
     */
    abstract public function calculatePay();
}