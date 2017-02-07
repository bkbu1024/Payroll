<?php

namespace Payroll\Contract;

use Payroll\Contract\Relation\HasEmployee;
use Payroll\Contract\Base\Identifiable;

interface SalesReceipt extends Identifiable, HasEmployee
{
    /**
     * @return string
     */
    public function getDate();

    /**
     * @return float
     */
    public function getAmount();

    /**
     * @param string $date
     */
    public function setDate($date);

    /**
     * @param float $amount
     */
    public function setAmount($amount);
}