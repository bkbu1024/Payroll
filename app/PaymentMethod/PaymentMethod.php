<?php

namespace Payroll\PaymentMethod;

interface PaymentMethod
{
    /**
     * @return string
     */
    public function getType();
}