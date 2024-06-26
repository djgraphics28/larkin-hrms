<?php

namespace App\Models;

use App\Models\User;
use App\Models\Asset;
use App\Models\Payroll;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Business extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * The departments that belong to the Business
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class);
    }

    /**
     * Get all of the employees for the Business
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'business_id', 'id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function ($query) use ($searchTerm) {

            $query->where('name', 'like', $searchTerm);
        });
    }

    /**
     * The users that belong to the Business
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function banks(): HasMany
    {
        return $this->hasMany(BusinessCompanyBank::class);
    }

    /**
     * Get all of the assets for the Business
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'business_id', 'id');
    }

    /**
     * Get all of the payrolls for the Business
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'business_id', 'id');
    }
}
