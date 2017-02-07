<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\Transaction\Transaction;

abstract class ChangeEmployee implements Transaction
{
    /**
     * @var Employee
     */
    protected $employee;

    /**
     * ChangeEmployee constructor.
     * @param Employee $employee
     */
    public function __construct(Employee $employee)
    {
        $this->employee = $employee;
    }

    /**
     * @return mixed
     */
    public function execute()
    {
        if ($this->employee) {
            return $this->change();
        }
        
        return false;
    }

    /**
     * @return Employee
     */
    abstract protected function change();
}