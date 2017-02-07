<?php

namespace Payroll\Contract\Relation;

use Illuminate\Support\Collection;
use Payroll\Contract\SalesReceipt;

interface HasSalesReceipts
{
    /**
     * @param SalesReceipt $salesReceipt
     * @return void
     */
    public function addSalesReceipt(SalesReceipt $salesReceipt);

    /**
     * @param string $date
     * @return mixed
     */
    public function getSalesReceiptBy($date);

    /**
     * @return Collection
     */
    public function getSalesReceipts();
}