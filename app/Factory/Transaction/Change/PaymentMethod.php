<?php

namespace Payroll\Factory\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\Transaction\Change\ChangeDirectMethod;
use Payroll\Transaction\Change\ChangeHoldMethod;
use Payroll\Transaction\Change\ChangeMailMethod;
use Payroll\Transaction\Change\ChangePaymentMethod;

class PaymentMethod
{
    /**
     * @param Employee $employee
     * @param array $data
     * @return ChangePaymentMethod
     */
    public static function create(Employee $employee, array $data = [])
    {
        $bank = array_get($data, 'bank');
        $account = array_get($data, 'account');
        $address = array_get($data, 'address');

        if ($bank && $account) {
            return new ChangeDirectMethod($employee, $bank, $account);
        } elseif ($address) {
            return new ChangeMailMethod($employee, $address);
        }

        return new ChangeHoldMethod($employee);
    }
}