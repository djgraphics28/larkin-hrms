<?php

namespace App\Models;

use App\Models\Workshift;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WeekDay extends Model
{
    use HasFactory;

    protected $guarded = [];
    public $timestamps = false;
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The workshifts that belong to the WeekDay
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function workshifts(): BelongsToMany
    {
        return $this->belongsToMany(Workshift::class);
    }
}
