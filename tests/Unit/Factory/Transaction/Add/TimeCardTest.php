<?php

namespace Unit\Factory\Transaction\Add;

use Payroll\Employee as EmployeeModel;
use Payroll\Factory\Transaction\Add\TimeCard as AddTimeCardFactory;
use Payroll\Tests\TestCase;
use Payroll\Transaction\Add\AddTimeCard;

class TimeCardTest extends TestCase
{
    public function testCreate()
    {
        $transaction = AddTimeCardFactory::create(new EmployeeModel, date('Y-m-d'), 8);
        $this->assertTrue($transaction instanceof AddTimeCard);
    }
}