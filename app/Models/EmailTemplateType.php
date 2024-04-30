<?php

namespace App\Models;

use App\Models\EmailTemplate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplateType extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get all of the email_templates for the EmailTemplateType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function email_templates(): HasMany
    {
        return $this->hasMany(EmailTemplate::class, 'email_template_type_id', 'id');
    }


    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function($query) use ($searchTerm){

            $query->where('name','like', $searchTerm);
        });

    }
}
