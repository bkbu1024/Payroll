<?php

namespace Payroll\Transaction\Change;

use Payroll\PaymentMethod\HoldMethod;

class ChangeHoldMethod extends ChangePaymentMethod
{
    /**
     * @return HoldMethod
     */
    protected function getPaymentMethod()
    {
        return new HoldMethod;
    }
}
