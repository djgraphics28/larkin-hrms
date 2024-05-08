<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Business;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'is_active',
        'is_admin',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function($query) use ($searchTerm){

            $query->where('name','like', $searchTerm)
            ->orWhere('email','like', $searchTerm);
        });

    }

    /**
     * The businesses that belong to the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class);
    }

    /**
     * Get the active_business associated with the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function active_business(): HasOne
    {
        return $this->hasOne(BusinessUser::class, 'user_id', 'id')->where('is_active', true)->select('business_id');
    }
}
