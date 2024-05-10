<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payrun extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->whereHas('fortnight', function ($query) use ($searchTerm) {

            $query->where('code', 'like', $searchTerm);
        });
    }

    public function fortnight(): BelongsTo
    {
        return $this->belongsTo(Fortnight::class, 'fortnight_id', 'id');
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    public function payslip(): HasMany
    {
        return $this->hasMany(Payslip::class);
    }
}
