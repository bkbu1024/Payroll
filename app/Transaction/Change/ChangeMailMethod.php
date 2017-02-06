<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\PaymentMethod\DirectMethod;
use Payroll\PaymentMethod\MailMethod;

class ChangeMailMethod extends ChangePaymentMethod
{
    /**
     * @var string
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
