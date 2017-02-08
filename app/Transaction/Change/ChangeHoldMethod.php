<?php

namespace Payroll\Transaction\Change;

use Payroll\PaymentMethod\Factory as MethodFactory;
use Payroll\PaymentMethod\PaymentMethod;

class ChangeHoldMethod extends ChangePaymentMethod
{
    /**
     * @return PaymentMethod
     */
    protected function getPaymentMethod()
    {
        return MethodFactory::createByData();
    }
}
