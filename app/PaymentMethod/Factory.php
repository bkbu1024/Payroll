<?php

namespace Payroll\PaymentMethod;

class Factory
{
    /**
     * @return PaymentMethod
     */
    public static function createDefault()
    {
        return new HoldMethod;
    }
}