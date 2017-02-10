<?php

namespace Payroll\PaymentMethod;

class HoldMethod implements PaymentMethod
{
    public function getType()
    {
        return 'HOLD';
    }

}