<?php

namespace Payroll\Contract\Relation;

use Payroll\Contract\TimeCard;
use Illuminate\Support\Collection;

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
