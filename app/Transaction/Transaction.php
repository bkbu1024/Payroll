<?php

namespace Payroll\Transaction;

interface Transaction
{
    /**
     * @return Payroll\Employee
     */
    public function execute();
}