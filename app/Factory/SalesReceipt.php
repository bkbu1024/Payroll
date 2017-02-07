<?php

namespace Payroll\Factory;

use Payroll\SalesReceipt as SalesReceiptModel;

class SalesReceipt
{
    public static function createSalesReceipt(array $data)
    {
        return new SalesReceiptModel([
            'date' => array_get($data, 'date'),
            'amount' => array_get($data, 'amount')
        ]);
    }
}