<?php

namespace Payroll;

use Illuminate\Database\Eloquent\Model;

class SalesReceipt extends Model implements Contract\SalesReceipt
{
    /**
     * @var array
     */
    protected $fillable = ['date', 'amount'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getEmployeeId()
    {
        return $this->employee_id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setEmployeeId($employeeId)
    {
        $this->employee_id = $employeeId;
    }

    /**
     * @param string $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }
}
