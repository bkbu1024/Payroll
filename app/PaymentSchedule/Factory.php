<?php

namespace Payroll\PaymentSchedule;

use Payroll\Contract\Employee;
use Payroll\PaymentClassification\HourlyClassification;
use Payroll\Transaction\Add\AddEmployee;

class Factory
{
    /**
     * @param Employee $employee
     * @return PaymentSchedule
     */
    public static function createSchedule(Employee $employee)
    {
        if ($employee->getType() == AddEmployee::SALARIED) {
            return new MonthlySchedule;
        } elseif ($employee->getType() == AddEmployee::HOURLY) {
            return new WeeklySchedule;
        } elseif ($employee->getType() == AddEmployee::COMMISSION) {
            return new BiweeklySchedule;
        }
    }
}