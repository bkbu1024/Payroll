<?php

namespace Payroll\Factory\Transaction\Add;

use Exception;
use Payroll\Transaction\Add\AddCommissionedEmployee;
use Payroll\Transaction\Add\AddEmployee;
use Payroll\Transaction\Add\AddHourlyEmployee;
use Payroll\Transaction\Add\AddSalariedEmployee;

class Employee
{
    /**
     * @param string[] $data
     * @return AddEmployee
     * @throws Exception
     */
    public static function create(array $data)
    {
        $salary = array_get($data, 'salary');
        $commissionRate = array_get($data, 'commissionRate');
        $hourlyRate = array_get($data, 'hourlyRate');

        if ($salary && $commissionRate) {
            return new AddCommissionedEmployee(
                $data['name'], $data['address'], $salary, $commissionRate);
        } elseif ($salary) {
            return new AddSalariedEmployee($data['name'], $data['address'], $salary);
        } elseif ($hourlyRate) {
            return new AddHourlyEmployee($data['name'], $data['address'], $hourlyRate);
        }

        throw new Exception('Never should reach here');
    }
}