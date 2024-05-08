<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payslip extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function payrun(): BelongsTo
    {
        return $this->belongsTo(Payrun::class);
    }

    public function fortnight(): BelongsTo
    {
        return $this->belongsTo(Fortnight::class);
    }
}
