<?php

namespace Payroll\PaymentClassification;

use Payroll\Contract\Employee;
use Payroll\Contract\Paycheck;
use Payroll\Factory\PaymentClassification\Factory;

class SalariedClassification extends PaymentClassification
{
    /**
     * @var float
     */
    private $salary;

    /**
     * @return float
     */
    public function getSalary()
    {
        return $this->salary;
    }

    /**
     * SalariedClassification constructor.
     * @param float $salary
     */
    public function __construct($salary)
    {
        $this->salary = $salary;
    }

    /**
     * @param Paycheck $paycheck
     * @return float
     */
    public function calculatePay(Paycheck $paycheck)
    {
        return $this->salary;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return Factory::SALARIED;
    }

    /**
     * @param Employee $employee
     */
    public function setEmployeeData(Employee $employee)
    {
        $employee->setSalary($this->salary);
    }
}