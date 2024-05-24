<?php

namespace App\Models;

use App\Models\Payroll;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Fortnight extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The employee_attendances that belong to the Fortnight
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function employee_attendances(): BelongsToMany
    {
        return $this->belongsToMany(Employee::class, 'attendances', 'fortnight_id', 'employee_number');
    }

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function ($query) use ($searchTerm) {

            $query->where('year', 'like', $searchTerm);
        });
    }

    /**
     * Get all of the payrolls for the Fortnight
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payrolls(): HasMany
    {
        return $this->hasMany(Payroll::class, 'fortnight_id', 'id');
    }

    public function fn_number()
    {
        $year = date('Y');
        $fn_number = Fortnight::where('year', $year)->count();
        return $fn_number;
    }
}
