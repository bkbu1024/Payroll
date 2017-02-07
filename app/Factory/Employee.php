<?php

namespace Payroll\Factory;

use Payroll\Employee as EmployeeModel;

class Employee
{
    const SALARIED = 'SALARIED';
    const HOURLY = 'HOURLY';
    const COMMISSION = 'COMMISSION';

    /**
     * @return EmployeeModel
     */
    public static function createEmployee()
    {
        return new EmployeeModel();
    }
}