<?php

namespace Payroll\PaymentClassification;

use Payroll\Contract\Employee;
use Exception;

class Factory
{
    /**
     * @param Employee $employee
     * @return CommissionedClassification|HourlyClassification|SalariedClassification
     * @throws Exception
     */
    public static function createClassificationByEmployee(Employee $employee)
    {
        $classification = self::createClassificationByData([
            'salary' => $employee->getSalary(),
            'commissionRate' => $employee->getCommissionRate(),
            'hourlyRate' => $employee->getHourlyRate(),
        ]);

        $classification->setEmployee($employee);

        return $classification;
    }

    /**
     * @param string[] $data
     * @return PaymentClassification
     * @throws Exception
     */
    public static function createClassificationByData(array $data)
    {
        $salary = array_get($data, 'salary');
        $commissionRate = array_get($data, 'commissionRate');
        $hourlyRate = array_get($data, 'hourlyRate');

        if ($salary && $commissionRate) {
            return new CommissionedClassification($salary, $commissionRate);
        } elseif ($salary) {
            return new SalariedClassification($salary);
        } elseif ($hourlyRate) {
            return new HourlyClassification($hourlyRate);
        }

        throw new Exception('Never should reach here');
    }
}
