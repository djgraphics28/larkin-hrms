<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CompanyBank extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function ($query) use ($searchTerm) {

            $query->where('account_name', 'like', $searchTerm);
        });
    }

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class);
    }
}
