<?php

namespace Payroll;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Payroll\Contract\SalesReceipt as SalesReceiptContract;

class SalesReceipt extends Model implements SalesReceiptContract
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

    /**
     * @param string $payDate
     * @return bool
     */
    public function isInPayPeriod($payDate)
    {
        $endDate = new DateTime($payDate);
        $startDate = clone $endDate;
        $startDate = $startDate->modify('-13 days');

        return (strtotime($this->getDate()) >= $startDate->getTimestamp()
            && strtotime($this->getDate()) <= $endDate->getTimestamp());
    }
}
