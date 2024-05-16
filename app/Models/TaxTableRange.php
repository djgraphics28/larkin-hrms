<?php

namespace App\Models;

use App\Models\TaxTable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxTableRange extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the tax_table that owns the TaxTableRange
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tax_table(): BelongsTo
    {
        return $this->belongsTo(TaxTable::class, 'tax_table_id', 'id');
    }
}
