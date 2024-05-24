<?php

namespace App\Models;

use App\Models\Payslip;
use App\Models\Business;
use App\Models\Fortnight;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payroll extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the payslips for the Payroll
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payslips(): HasMany
    {
        return $this->hasMany(Payslip::class, 'payroll_id', 'id');
    }

    /**
     * Get the fortnight that owns the Payroll
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fortnight(): BelongsTo
    {
        return $this->belongsTo(Fortnight::class, 'fortnight_id', 'id');
    }

    /**
     * Get the business that owns the Payroll
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }
}
