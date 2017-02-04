<?php

namespace Payroll\PaymentClassification;

interface PaymentClassification
{
    /**
     * @return float
     */
    public function calculatePay();
}