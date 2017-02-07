<?php

namespace Payroll\Contract;

use Payroll\Contract\Relation\HasEmployee;
use Payroll\Contract\Base\Identifiable;

interface TimeCard extends Identifiable, HasEmployee
{
    /**
     * @return string
     */
    public function getDate();

    /**
     * @return float
     */
    public function getHours();

    /**
     * @param string $date
     */
    public function setDate($date);

    /**
     * @param float $hours
     */
    public function setHours($hours);
}