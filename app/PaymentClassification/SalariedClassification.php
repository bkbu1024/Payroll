<?php

namespace Payroll\PaymentClassification;

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
     * @return float
     */
    public function calculatePay()
    {
        return 0;
    }
}