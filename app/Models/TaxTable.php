<?php

namespace App\Models;

use App\Models\TaxTableRange;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TaxTable extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function ($query) use ($searchTerm) {

            $query->where('description', 'like', $searchTerm);
        });
    }

    /**
     * Get all of the tax_table_ranges for the TaxTable
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tax_table_ranges(): HasMany
    {
        return $this->hasMany(TaxTableRange::class, 'tax_table_id', 'id');
    }
}
