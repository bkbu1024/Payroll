<?php

namespace Payroll;

use Illuminate\Database\Eloquent\Model;

class TimeCard extends Model
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
}