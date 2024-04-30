<?php

namespace App\Models;

use App\Models\EmailTemplateType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailTemplate extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the email_template_type that owns the EmailTemplate
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function email_template_type(): BelongsTo
    {
        return $this->belongsTo(EmailTemplateType::class, 'email_template_type_id', 'id');
    }

    public function scopeSearch($query, $searchTerm)
    {
        $searchTerm = "%$searchTerm%";

        $query->where(function($query) use ($searchTerm){

            $query->where('title','like', $searchTerm)
                ->orWhere('subject','like', $searchTerm);
        });

    }
}
