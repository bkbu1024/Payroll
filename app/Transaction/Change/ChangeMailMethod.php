<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\PaymentMethod\PaymentMethod;
use Payroll\PaymentMethod\Factory as MethodFactory;

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
     * @return PaymentMethod
     */
    protected function getPaymentMethod()
    {
        return MethodFactory::createByData([
            'address' => $this->address
        ]);
    }
}
