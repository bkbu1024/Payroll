<?php

namespace Payroll\Transaction;

use Payroll\Employee;

class PayDay implements Transaction
{
    /**
     * @var string
     */
    private $payDate;

    /**
     * @var array
     */
    private $paychecks = [];

    /**
     * PayDay constructor.
     * @param string $payDate
     */
    public function __construct($payDate)
    {
        $this->payDate = $payDate;
    }

    public function execute()
    {
        $employees = Employee::all();
        foreach ($employees as $employee) {
            if ($employee->isPayDay($this->payDate)) {
                $paycheck = new Paycheck($this->payDate);
                $this->paychecks[$employee->getId()] = $paycheck;
                $employee->payDay($paycheck);
            }
        }
    }
}