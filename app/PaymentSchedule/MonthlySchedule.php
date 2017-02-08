<?php

namespace Payroll\PaymentSchedule;

use DateTime;

class MonthlySchedule implements PaymentSchedule
{
    /**
     * @param string $payDate
     * @return bool
     */
    public function isPayDay($payDate)
    {
        return $this->isLastDayOfMonth($payDate);
    }

    /**
     * @param string $payDate
     * @return bool
     */
    protected function isLastDayOfMonth($payDate)
    {
        $date = new DateTime($payDate);
        $lastDay = $date->format('Y-m-t');
        $currentDay = $date->format('Y-m-d');

        return $lastDay == $currentDay;
    }

}