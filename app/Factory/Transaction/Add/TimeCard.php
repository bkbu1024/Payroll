<?php

namespace Payroll\Factory\Transaction\Add;

use Payroll\Contract\Employee as EmployeeContract;
use Payroll\Transaction\Add\AddTimeCard;

class TimeCard
{
    /**
     * @param EmployeeContract $employee
     * @param string $date
     * @param float $hours
     * @return AddTimeCard
     */
    public static function create(EmployeeContract $employee, $date, $hours)
    {
        return new AddTimeCard($date, $hours, $employee);
    }
}