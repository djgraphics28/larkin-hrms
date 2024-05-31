<?php

namespace App\Models;

use App\Models\BusinessCompensation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompensationItem extends Model
{
    use HasFactory;

    protected $guarded = [];


    /**
     * The businesses that belong to the CompensationItem
     *
     * @return BelongsToMany
     */
    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, 'business_compensation', 'compensation_item_id', 'business_id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function ($query) use ($searchTerm) {

            $query->where('name', 'like', $searchTerm);
        });
    }

    /**
     * Get all of the Business_compensations for the CompensationItem
     *
     * @return HasMany
     */
    public function Business_compensations(): HasMany
    {
        return $this->hasMany(BusinessCompensation::class, 'compensation_item_id', 'id');
    }
}
