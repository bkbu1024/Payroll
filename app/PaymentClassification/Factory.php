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
        $classification = null;
        if ($employee->getType() == AddEmployee::COMMISSION) {
            $classification = new CommissionedClassification($employee->getSalary(), $employee->getCommissionRate());
        } elseif ($employee->getType() == AddEmployee::SALARIED) {
            $classification = new SalariedClassification($employee->getSalary());
        } elseif ($employee->getType() == AddEmployee::HOURLY) {
            $classification =new HourlyClassification($employee->getHourlyRate());
        }

        $classification->setEmployee($employee);

        return $classification;
    }
}