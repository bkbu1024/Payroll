<?php

namespace Payroll\Contract;

use Payroll\Contract\Base\Identifiable;
use Payroll\Contract\Relation\HasEmployee;

interface Paycheck extends Identifiable, HasEmployee
{
    public function getDate();
    public function setDate($date);
    public function getNetPay();
    public function setNetPay($netPay);
    public function getType();
    public function setType($type);
}