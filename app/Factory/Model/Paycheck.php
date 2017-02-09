<?php

namespace Payroll\Factory\Model;

use Payroll\Paycheck as PaycheckModel;

class Paycheck
{
    /**
     * @param string|null $date
     * @return PaycheckModel
     */
    public static function create($date = null)
    {
        if ($date) {
            return new PaycheckModel(['date' => $date]);
        } else {
            return new PaycheckModel;
        }
    }
}