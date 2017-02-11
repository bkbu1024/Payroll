<?php

namespace Payroll\Contract;

use Payroll\Contract\Base\CanSave;
use Payroll\Contract\Base\Identifiable;
use Payroll\Contract\Relation\HasEmployee;

interface Paycheck extends Identifiable, HasEmployee, CanSave
{
    public function getDate();
    public function setDate($date);
    public function getNetPay();
    public function setNetPay($netPay);
    public function getType();
    public function setType($type);
    public function toArray();
}