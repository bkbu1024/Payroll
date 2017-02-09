<?php

namespace Payroll\Factory\Transaction\Add;

use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Transaction\Add\AddSalesReceipt;

class SalesReceipt
{
    /**
     * @param EmployeeContract $employee
     * @param string $date
     * @param string $amount
     * @return AddSalesReceipt
     */
    public static function create(EmployeeContract $employee, $date, $amount)
    {
        return new AddSalesReceipt($date, $amount, $employee);
    }
}