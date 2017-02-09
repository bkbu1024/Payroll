<?php

namespace Payroll\Factory\PaymentMethod;

use Payroll\PaymentMethod\DirectMethod;
use Payroll\PaymentMethod\HoldMethod;
use Payroll\PaymentMethod\MailMethod;

class Factory
{
    /**
     * @param array $data
     * @return PaymentMethod
     */
    public static function createByData(array $data = [])
    {
        $address = array_get($data, 'address');
        $bank = array_get($data, 'bank');
        $account = array_get($data, 'account');

        if ($address) {
            return new MailMethod($address);
        } elseif ($bank && $account) {
            return new DirectMethod($bank, $account);
        }

        return new HoldMethod;
    }
}