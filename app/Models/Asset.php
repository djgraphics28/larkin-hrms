<?php

namespace App\Models;

use App\Models\Business;
use App\Models\Employee;
use App\Models\AssetType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'is_working' => 'boolean',
    ];

    /**
     * Get the asset_type that owns the Asset
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function asset_type(): BelongsTo
    {
        return $this->belongsTo(AssetType::class, 'asset_type_id', 'id');
    }

    /**
     * Get the business that owns the Asset
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class, 'business_id', 'id');
    }

    /**
     * Get the employee that owns the Asset
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function($query) use ($searchTerm){

            $query->where('name','like', $searchTerm)
                ->orWhere('asset_code','like', $searchTerm);
        });

    }
}
