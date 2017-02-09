<?php

namespace Payroll\Factory\Transaction\Change;

use Exception;
use Payroll\Contract\Employee;
use Payroll\Transaction\Change\ChangeCommissionedPaymentClassification;
use Payroll\Transaction\Change\ChangeHourlyPaymentClassification;
use Payroll\Transaction\Change\ChangePaymentClassification;
use Payroll\Transaction\Change\ChangeSalariedPaymentClassification;

class PaymentClassification
{
    /**
     * @param Employee $employee
     * @param array $data
     * @return ChangePaymentClassification
     * @throws Exception
     */
    public static function create(Employee $employee, array $data)
    {
        $salary = array_get($data, 'salary');
        $commissionRate = array_get($data, 'commissionRate');
        $hourlyRate = array_get($data, 'hourlyRate');

        if ($salary && $commissionRate) {
            return new ChangeCommissionedPaymentClassification($employee, $salary, $commissionRate);
        } elseif ($salary) {
            return new ChangeSalariedPaymentClassification($employee, $salary);
        } elseif ($hourlyRate) {
            return new ChangeHourlyPaymentClassification($employee, $hourlyRate);
        }

        throw new Exception('Never should reach here');
    }
}