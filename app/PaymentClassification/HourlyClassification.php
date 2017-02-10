<?php

namespace Payroll\PaymentClassification;

use DateTime;
use Payroll\Contract\Paycheck;
use Payroll\Contract\TimeCard;
use Illuminate\Database\Eloquent\Collection;

class HourlyClassification extends PaymentClassification
{
    /**
     * @var float
     */
    private $hourlyRate;

    /**
     * @return float
     */
    public function getHourlyRate()
    {
        return $this->hourlyRate;
    }

    /**
     * HourlyClassification constructor.
     *
     * @param float $hourlyRate
     */
    public function __construct($hourlyRate)
    {
        $this->hourlyRate = $hourlyRate;
    }

    /**
     * @param Paycheck $paycheck
     * @return float
     */
    public function calculatePay(Paycheck $paycheck)
    {
        $timeCards = $this->employee->getTimeCards();
        $netPay = 0;

        foreach ($timeCards as $timeCard) {
            /**
             * @var TimeCard $timeCard
             */
            if ($timeCard->isInPayPeriod($paycheck->getDate())) {
                $netPay += $this->calculatePayForTimeCard($timeCard);
            }
        }

        return $netPay;
    }

    /**
     * @param TimeCard $timeCard
     * @return float
     */
    protected function calculatePayForTimeCard(TimeCard $timeCard)
    {
        $hours = $timeCard->getHours();
        $overtime = max(0, $hours - 8);
        $straightTime =  $hours - $overtime;

        return $straightTime * $this->hourlyRate + $overtime * $this->hourlyRate * 1.5;
    }

    /**
     * @param TimeCard $timeCard
     */
    public function addTimeCard(TimeCard $timeCard)
    {
        $timeCard->setEmployeeId($this->employee->getId());
        $this->employee->addTimeCard($timeCard);
    }

    /**
     * @param string $date
     *
     * @return mixed
     */
    public function getTimeCard($date)
    {
        return $this->employee->getTimeCardBy($date);
    }

    /**
     * @return Collection
     */
    public function timeCards()
    {
        return $this->employee->getTimeCards();
    }
}
