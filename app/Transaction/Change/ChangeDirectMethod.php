<?php

namespace Payroll\Transaction\Change;

use Payroll\Employee;
use Payroll\PaymentMethod\DirectMethod;

class ChangeDirectMethod extends ChangePaymentMethod
{
    /**
     * @var string
     */
    private $bank;
    /**
     * @var string
     */
    private $account;

    /**
     * ChangeDirectMethod constructor.
     * @param Employee $employee
     * @param $bank
     * @param $account
     */
    public function __construct(Employee $employee, $bank, $account)
    {
        parent::__construct($employee);
        $this->bank = $bank;
        $this->account = $account;
    }

    /**
     * @return DirectMethod
     */
    protected function getPaymentMethod()
    {
        return new DirectMethod($this->bank, $this->account);
    }
}
