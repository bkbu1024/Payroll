<?php

namespace Payroll\Transaction;

use Payroll\Employee;
use Payroll\Contract\Paycheck;
use Payroll\Factory\Paycheck as PaycheckFactory;

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
            /**
             * @var Employee $employee
             */
            if ($employee->isPayDay($this->payDate)) {
                $paycheck = PaycheckFactory::create($this->payDate);
                $this->paychecks[$employee->getId()] = $paycheck;
                $employee->payday($paycheck);
            }
        }
    }

    /**
     * @param $employeeId
     * @return Paycheck
     */
    public function getPayCheck($employeeId)
    {
        return array_get($this->paychecks, $employeeId);
    }
}