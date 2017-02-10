<?php

namespace Tests\Unit\Factory\Model;

use Payroll\Contract\SalesReceipt as SalesReceiptContract;
use Payroll\Factory\Model\SalesReceipt as SalesReceiptFactory;
use Payroll\Tests\TestCase;

class SalesReceiptTest extends TestCase
{
    public function testCreateSalesReceipt()
    {
        $data = [
            'date' => date('Y-m-d'),
            'amount' => 1000
        ];

        $salesReceipt = SalesReceiptFactory::createSalesReceipt($data);
        $this->assertTrue($salesReceipt instanceof SalesReceiptContract);
        $this->assertEquals(date('Y-m-d'), $salesReceipt->getDate());
        $this->assertEquals(1000, $salesReceipt->getAmount());
    }
}