<?php

namespace Payroll\PaymentSchedule;

use DateInterval;
use DatePeriod;
use DateTime;

class BiweeklySchedule implements PaymentSchedule
{
    public function isPayDay($payDate)
    {
        return $this->isDateBelongsToSecondFridaysInMonth($payDate);
    }

    /**
     * @param string $payDate
     * @return bool
     */
    protected function isDateBelongsToSecondFridaysInMonth($payDate)
    {
        $step = 2;
        $unit = 'W';

        $start = new DateTime($payDate);
        $start->modify('first day of this month');
        $end = clone $start;

        // PHP DateTime Bugfix
        // If a month starts with Friday (2017 September), then second friday will return 09-15 instead of 09-08
        if ($start->format('D') == 'Fri') {
            $start->modify('first friday');
        } else {
            $start->modify('second friday');
        }

        $end->add(new DateInterval('P1M'));

        $interval = new DateInterval("P{$step}{$unit}");
        $period = new DatePeriod($start, $interval, $end);

        $periodArray = [];
        foreach ($period as $date) {
            $periodArray[] = $date;
        }

        $search = array_search(new DateTime($payDate), $periodArray);

        return $search !== false;
    }
}