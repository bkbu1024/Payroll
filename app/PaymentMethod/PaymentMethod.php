<?php

namespace Payroll\PaymentMethod;

use Payroll\Contract\Paycheck;

interface PaymentMethod
{
    public function pay(Paycheck $paycheck);

    /**
     * @return string
     */
    public function getType();
}