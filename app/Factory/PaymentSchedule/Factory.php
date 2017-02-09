<?php

namespace Payroll\Factory\PaymentSchedule;

use Exception;
use Payroll\Contract\Employee;
use Payroll\PaymentSchedule\BiweeklySchedule;
use Payroll\PaymentSchedule\MonthlySchedule;
use Payroll\PaymentSchedule\WeeklySchedule;

class Factory
{
    /**
     * @param Employee $employee
     * @return PaymentSchedule
     */
    public static function createScheduleByEmployee(Employee $employee)
    {
        $schedule = self::createScheduleByData([
            'salary' => $employee->getSalary(),
            'commissionRate' => $employee->getCommissionRate(),
            'hourlyRate' => $employee->getHourlyRate(),
        ]);

        return $schedule;
    }

    /**
     * @param string[] $data
     * @return PaymentClassification
     * @throws Exception
     */
    public static function createScheduleByData(array $data)
    {
        $salary = array_get($data, 'salary');
        $commissionRate = array_get($data, 'commissionRate');
        $hourlyRate = array_get($data, 'hourlyRate');

        if ($salary && $commissionRate) {
            return new BiweeklySchedule;
        } elseif ($salary) {
            return new MonthlySchedule;
        } elseif ($hourlyRate) {
            return new WeeklySchedule;
        }

        throw new Exception('Never should reach here');
    }
}