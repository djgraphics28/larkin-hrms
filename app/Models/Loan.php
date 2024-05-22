<?php

namespace App\Models;

use App\Models\Business;
use App\Models\Employee;
use App\Models\LoanType;
use App\Models\LoanPayment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the loan_type that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function loan_type(): BelongsTo
    {
        return $this->belongsTo(LoanType::class, 'loan_type_id', 'id');
    }

    /**
     * Get the employee that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /**
     * Get the business that owns the Loan
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    /**
     * Get all of the loan_payments for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loan_payments(): HasMany
    {
        return $this->hasMany(LoanPayment::class, 'loan_id', 'id');
    }

}
