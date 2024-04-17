<?php

namespace App\Models;

use App\Models\LeaveCredit;
use App\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LeaveType extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get all of the leave_credits for the LeaveType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leave_credits(): HasMany
    {
        return $this->hasMany(LeaveCredit::class, 'leave_type_id', 'id');
    }

    /**
     * Get all of the leave_requests for the LeaveType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leave_requests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'leave_type_id', 'id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function($query) use ($searchTerm){

            $query->where('name','like', $searchTerm);
        });

    }
}
