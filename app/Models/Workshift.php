<?php

namespace App\Models;

use App\Models\WeekDay;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Workshift extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The week_days that belong to the Workshift
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function day_offs(): BelongsToMany
    {
        return $this->belongsToMany(WeekDay::class);
    }

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function ($query) use ($searchTerm) {

            $query->where('title', 'like', $searchTerm);
        });
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}
