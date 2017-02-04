<?php

namespace Payroll\PaymentClassification;

class CommissionedClassification implements PaymentClassification
{
    /**
     * @return float
     */
    public function calculatePay()
    {
        return 0;
    }
}