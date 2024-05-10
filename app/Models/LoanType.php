<?php

namespace App\Models;

use App\Models\Loan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class LoanType extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the loans for the LoanType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class, 'loan_type_id', 'id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function ($query) use ($searchTerm) {

            $query->where('name', 'like', $searchTerm);
        });

    }
}
