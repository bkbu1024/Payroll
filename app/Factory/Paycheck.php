<?php

namespace Payroll\Factory;

use Payroll\Paycheck as PaycheckModel;

class Paycheck
{
    public static function create($date)
    {
        return new PaycheckModel(['date' => $date]);
    }
}