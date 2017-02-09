<?php

namespace Unit\Factory\Transaction\Add;

use Payroll\Employee;
use Payroll\Factory\Transaction\Add\SalesReceipt;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddSalesReceipt;

class SalesReceiptTest extends TestCase
{
    public function testCreate()
    {
        $transaction = SalesReceipt::create(new Employee, date('Y-m-d'), 1000);
        $this->assertTrue($transaction instanceof AddSalesReceipt);
    }
}