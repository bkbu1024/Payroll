<?php

namespace Payroll\Contract\Relation;

use Illuminate\Support\Collection;
use Payroll\Contract\TimeCard;

interface HasTimeCards
{
    /**
     * @param TimeCard $timeCard
     */
    public function addTimeCard(TimeCard $timeCard);

    /**
     * @param string $date
     *
     * @return TimeCard
     */
    public function getTimeCardBy($date);

    /**
     * @return Collection
     */
    public function getTimeCards();
}
