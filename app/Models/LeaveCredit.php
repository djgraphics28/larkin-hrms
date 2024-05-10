<?php

namespace App\Models;

use App\Models\Employee;
use App\Models\LeaveType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveCredit extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the leave_type that owns the LeaveCredit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function leave_type(): BelongsTo
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
    }

    /**
     * Get the employee that owns the LeaveCredit
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }
}
