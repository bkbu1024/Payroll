<?php

namespace Payroll;

use Illuminate\Database\Eloquent\Model;
use Payroll\Contract\Paycheck as PaycheckContract;

class Paycheck extends Model implements PaycheckContract
{
    protected $fillable = ['date', 'net_pay', 'type'];

    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    public function setEmployeeId($employeeId)
    {
        $this->employee_id = $employeeId;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
    }

    public function getNetPay()
    {
        return $this->net_pay;
    }

    public function setNetPay($netPay)
    {
        $this->net_pay = $netPay;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }
}
