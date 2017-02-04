<?php

namespace Payroll\PaymentClassification;

class SalariedClassification implements PaymentClassification
{
    /**
     * @return float
     */
    public function calculatePay()
    {
        return 0;
    }
}