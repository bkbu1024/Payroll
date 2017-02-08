<?php

namespace Payroll\PaymentSchedule;

interface PaymentSchedule
{
    public function isPayDay($payDate);
}