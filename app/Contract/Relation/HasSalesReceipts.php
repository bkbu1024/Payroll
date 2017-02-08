<?php

namespace Payroll\Contract\Relation;

use Payroll\Contract\SalesReceipt;
use Illuminate\Support\Collection;

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