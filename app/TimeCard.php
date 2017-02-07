<?php

namespace Payroll;

use Illuminate\Database\Eloquent\Model;

class TimeCard extends Model implements \Payroll\Contract\TimeCard
{
    /**
     * @var array
     */
    protected $fillable = ['date', 'hours'];

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

    public function getHours()
    {
        return $this->hours;
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

    public function setHours($hours)
    {
        $this->hours = $hours;
    }
}
