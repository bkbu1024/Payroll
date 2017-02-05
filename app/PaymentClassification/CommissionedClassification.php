<?php

namespace Payroll\PaymentClassification;

use Payroll\Employee;

class CommissionedClassification extends PaymentClassification
{
    /**
     * @var float
     */
    private $salary;
    /**
     * @var float
     */
    private $commission;

    /**
     * CommissionedClassification constructor.
     * @param float $salary
     * @param float $commission
     */
    public function __construct($salary, $commission)
    {
        $this->salary = $salary;
        $this->commission = $commission;
    }

    /**
     * @return float
     */
    public function calculatePay()
    {
        return 0;
    }
}