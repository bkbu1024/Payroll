<?php

namespace Payroll\Contract\Relation;

use Payroll\Contract\Paycheck;
use Payroll\Contract\SalesReceipt;
use Illuminate\Support\Collection;

interface HasPaychecks
{
    /**
     * @param Paycheck $paycheck
     * @return void
     */
    public function addPaycheck(Paycheck $paycheck);

    /**
     * @param string $date
     * @return Paycheck
     */
    public function getPaycheckBy($date);

    /**
     * @return Collection
     */
    public function getPaychecks();
}