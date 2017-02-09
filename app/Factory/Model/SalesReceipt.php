<?php

namespace Payroll\Factory\Model;

use Payroll\SalesReceipt as SalesReceiptModel;

class SalesReceipt
{
    /**
     * @param array $data
     * @return SalesReceiptModel
     */
    public static function createSalesReceipt(array $data)
    {
        return new SalesReceiptModel($data);
    }
}