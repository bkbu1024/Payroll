<?php

namespace Payroll\PaymentClassification;

use Payroll\Contract\Paycheck;

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
}