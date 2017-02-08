<?php

namespace Payroll\PaymentClassification;

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
     * @return float
     */
    public function calculatePay()
    {
        return 0;
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
