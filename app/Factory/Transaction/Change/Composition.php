<?php

namespace Payroll\Factory\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\Transaction\Change\ChangeComposition;

class Composition
{
    /**
     * @param Employee $originalEmployee
     * @param array $data
     * @return ChangeComposition
     */
    public static function create(Employee $originalEmployee, array $data)
    {
        $composition = new ChangeComposition($originalEmployee);
        if ($originalEmployee->getPaymentClassification()->getType()
            != $data['payment_classification']) {
            $composition->addTransaction(PaymentClassification::create($originalEmployee, $data));
        }

        if ($originalEmployee->getPaymentMethod()->getType()
            != $data['payment_method']) {
            $composition->addTransaction(PaymentMethod::create($originalEmployee, $data));
        }

        return $composition;
    }
}