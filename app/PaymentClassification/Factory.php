<?php

namespace Payroll\PaymentClassification;

use Payroll\Contract\Employee;
use Payroll\Transaction\Add\AddEmployee;
use \Exception;

class Factory
{
    /**
     * @param Employee $employee
     * @return CommissionedClassification|HourlyClassification|SalariedClassification
     * @throws Exception
     */
    public static function createClassification(Employee $employee)
    {
        if ($employee->getType() == AddEmployee::COMMISSION) {
            return new CommissionedClassification($employee->getSalary(), $employee->getCommissionRate());
        } elseif ($employee->getType() == AddEmployee::SALARIED) {
            return new SalariedClassification($employee->getSalary());
        } elseif ($employee->getType() == AddEmployee::HOURLY) {
            return new HourlyClassification($employee->getHourlyRate());
        }

        throw new Exception('Never should reach here');
    }
}