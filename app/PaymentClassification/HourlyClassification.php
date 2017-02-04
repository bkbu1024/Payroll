<?php

namespace Payroll\PaymentClassification;

class HourlyClassification implements PaymentClassification
{
    /**
     * @return float
     */
    public function calculatePay()
    {
        return 0;
    }
}