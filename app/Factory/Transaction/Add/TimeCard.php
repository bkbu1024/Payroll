<?php

namespace Payroll\Factory\Transaction\Add;

use Payroll\Contract\Employee;
use Payroll\Transaction\Add\AddTimeCard;

class TimeCard
{
    /**
     * @param Employee $employee
     * @param string $date
     * @param float $hours
     * @return AddTimeCard
     */
    public static function create(Employee $employee, $date, $hours)
    {
        return new AddTimeCard($date, $hours, $employee);
    }
}