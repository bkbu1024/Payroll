<?php

namespace Payroll\PaymentMethod;

class HoldMethod implements PaymentMethod
{
    public function pay()
    {
        // TODO: Implement pay() method.
    }

    public function getType()
    {
        return 'HOLD';
    }

}