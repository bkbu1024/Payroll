<?php

namespace Payroll;

use Illuminate\Database\Eloquent\Model;

class SalesReceipt extends Model
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
}
