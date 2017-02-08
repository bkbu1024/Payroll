<?php

namespace Payroll\PaymentMethod;

class DirectMethod implements PaymentMethod
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
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * @return string
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * DirectMethod constructor.
     * @param $bank
     * @param $account
     */
    public function __construct($bank, $account)
    {
        $this->bank = $bank;
        $this->account = $account;
    }

    public function pay()
    {
        // TODO: Implement pay() method.
    }

    public function getType()
    {
        return 'DIRECT';
    }
}