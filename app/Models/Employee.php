<?php

namespace App\Models;

use App\Models\Workshift;
use App\Models\BankDetail;
use App\Models\LeaveCredit;
use App\Models\LeaveRequest;
use App\Models\SalaryHistory;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $guarded = [];

    /**
     * Get the business that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    /**
     * Get the department that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    /**
     * Get the designation that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function designation(): BelongsTo
    {
        return $this->belongsTo(Designation::class, 'designation_id', 'id');
    }

    /**
     * Get the employee_status that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee_status(): BelongsTo
    {
        return $this->belongsTo(EmployeeStatus::class, 'employee_status_id', 'id');
    }

    /**
     * Get all of the attendances for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_number', 'employee_number');
    }

    /**
     * Get the workshift that owns the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function workshift(): BelongsTo
    {
        return $this->belongsTo(Workshift::class, 'workshift_id', 'id');
    }

    /**
     * Get all of the salary for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function salaries(): HasMany
    {
        return $this->hasMany(SalaryHistory::class, 'employee_id', 'id');
    }

    /**
     * Get all of the bank_details for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bank_details(): HasMany
    {
        return $this->hasMany(BankDetail::class, 'employee_id', 'id');
    }

    /**
     * Get all of the leave_credits for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leave_credits(): HasMany
    {
        return $this->hasMany(LeaveCredit::class, 'employee_id', 'id');
    }

    /**
     * Get all of the leave_requests for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function leave_requests(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'employee_id', 'id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function ($query) use ($searchTerm) {

            $query->where('first_name', 'like', $searchTerm)
                ->orWhere('last_name', 'like', $searchTerm);
        });
    }

    public function employee_hours(): HasMany
    {
        return $this->hasMany(EmployeeHours::class, 'employee_id', 'id');
    }

    public function payslip(): HasMany
    {
        return $this->hasMany(Payslip::class, 'employee_id', 'id');
    }
}
