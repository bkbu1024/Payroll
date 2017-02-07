<?php

namespace Payroll\Factory;

use Payroll\Employee as EmployeeModel;

class Employee
{
    /**
     * @return EmployeeModel
     */
    public static function createEmployee()
    {
        return new EmployeeModel();
    }
}