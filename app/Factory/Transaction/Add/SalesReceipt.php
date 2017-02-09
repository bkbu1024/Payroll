<?php

namespace Payroll\Factory\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\Transaction\Add\AddSalesReceipt;

class SalesReceipt
{
    /**
     * @param Employee $employee
     * @param string $date
     * @param string $amount
     * @return AddSalesReceipt
     */
    public static function create(Employee $employee, $date, $amount)
    {
        return new AddSalesReceipt($date, $amount, $employee);
    }
}