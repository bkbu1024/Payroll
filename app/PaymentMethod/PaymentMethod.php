<?php

namespace Payroll\PaymentMethod;

interface PaymentMethod
{
    public function pay();

    /**
     * @return string
     */
    public function getType();
}