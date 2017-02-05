<?php

namespace Payroll\PaymentClassification;

use Payroll\Employee;

class SalariedClassification extends PaymentClassification
{
    /**
     * @var float
     */
    private $salary;

    /**
     * SalariedClassification constructor.
     * @param float $salary
     */
    public function __construct($salary)
    {
        $this->salary = $salary;
    }

    /**
     * @return float
     */
    public function calculatePay()
    {
        return 0;
    }
}