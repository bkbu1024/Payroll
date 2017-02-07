<?php

namespace Payroll\Factory;

use Payroll\Employee as EmployeeModel;

class Employee
{
    const COMMISSION = 'COMMISSION';
    const SALARIED = 'SALARIED';
    const HOURLY = 'HOURLY';


    /**
     * @return EmployeeModel
     */
    public static function createEmployee()
    {
        return new EmployeeModel();
    }
}