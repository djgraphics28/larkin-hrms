<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BusinessCompensation extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the compensations that owns the BusinessCompensation
     *
     * @return BelongsTo
     */
    public function compensation(): BelongsTo
    {
        return $this->belongsTo(CompensationItem::class, 'compensation_item_id', 'id');
    }
}
