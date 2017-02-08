<?php

namespace Payroll\PaymentMethod;

use Payroll\Contract\Paycheck;

class MailMethod implements PaymentMethod
{
    /**
     * @var string
     */
    private $address;

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * MailMethod constructor.
     * @param $address
     */
    public function __construct($address)
    {
        $this->address = $address;
    }

    public function pay(Paycheck $paycheck)
    {
        // TODO: Implement pay() method.
    }

    public function getType()
    {
        return 'MAIL';
    }
}