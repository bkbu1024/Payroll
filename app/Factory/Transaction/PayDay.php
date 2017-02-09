<?php

namespace Payroll\Factory\Transaction;

use Payroll\Transaction\PayDay as PayDayTransaction;

class PayDay
{
    /**
     * @param $payDate
     * @return PayDayTransaction
     */
    public static function createPayDay($payDate)
    {
        return new PayDayTransaction($payDate);
    }
}