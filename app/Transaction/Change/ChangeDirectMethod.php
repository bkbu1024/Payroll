<?php

namespace Payroll\Transaction\Change;

use Payroll\Contract\Employee;
use Payroll\PaymentMethod\PaymentMethod;
use Payroll\PaymentMethod\Factory as MethodFactory;

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
     * @return PaymentMethod
     */
    protected function getPaymentMethod()
    {
        return MethodFactory::createByData([
            'bank' => $this->bank,
            'account' => $this->account
        ]);
    }
}
