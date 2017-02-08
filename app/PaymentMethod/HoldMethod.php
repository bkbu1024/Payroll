<?php

namespace Payroll\PaymentMethod;

use Payroll\Contract\Paycheck;

class HoldMethod implements PaymentMethod
{
    public function pay(Paycheck $paycheck)
    {
        // TODO: Implement pay() method.
    }

    public function getType()
    {
        return 'HOLD';
    }

}