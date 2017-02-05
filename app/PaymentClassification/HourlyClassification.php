<?php

namespace Payroll\PaymentClassification;

use Payroll\TimeCard;

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
        $timeCard->employee_id = $this->employee->id;
        $this->employee->timeCards()->save($timeCard);
    }

    /**
     * @param string $date
     *
     * @return mixed
     */
    public function getTimeCard($date)
    {
        return $this->employee->timeCards()->where('date', $date)->get()->first();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeCards()
    {
        return $this->employee->timeCards();
    }
}
