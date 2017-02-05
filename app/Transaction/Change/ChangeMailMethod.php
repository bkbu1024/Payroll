<?php

namespace Payroll\Transaction\Change;

use Payroll\Employee;
use Payroll\PaymentMethod\DirectMethod;
use Payroll\PaymentMethod\MailMethod;

class ChangeMailMethod extends ChangePaymentMethod
{
    /**
     * @var
     */
    private $address;

    /**
     * ChangeMailMethod constructor.
     *
     * @param Employee $employee
     * @param $address
     */
    public function __construct(Employee $employee, $address)
    {
        parent::__construct($employee);
        $this->address = $address;
    }

    /**
     * @return DirectMethod
     */
    protected function getPaymentMethod()
    {
        return new MailMethod($this->address);
    }
}
