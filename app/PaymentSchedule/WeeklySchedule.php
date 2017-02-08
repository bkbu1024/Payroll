<?php

namespace Payroll\PaymentSchedule;

use DateTime;

class WeeklySchedule implements PaymentSchedule
{
    /**
     * @param string $payDate
     * @return bool
     */
    public function isPayDay($payDate)
    {
        return $this->isFriday($payDate);
    }

    /**
     * @param string $payDate
     * @return bool
     */
    protected function isFriday($payDate)
    {
        $date = new DateTime($payDate);
        return $date->format('D') == 'Fri';
    }
}