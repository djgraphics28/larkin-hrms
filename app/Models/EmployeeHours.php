<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EmployeeHours extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function salary(): BelongsTo
    {
        return $this->BelongsTo(SalaryHistory::class);
    }
}
