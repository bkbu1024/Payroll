<?php

namespace Payroll\Factory\Model;

use Payroll\TimeCard as TimeCardModel;

class TimeCard
{
    /**
     * @param array $data
     * @return TimeCardModel
     */
    public static function createTimeCard(array $data)
    {
        return new TimeCardModel($data);
    }
}