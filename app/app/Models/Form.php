<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    protected $fillable = [
        'title',
        'form_image',
        'description',
        'status',
        'visibility',
        'created_by',
        'updated_by',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class)->ordered();
    }

    public function formSubmissions()
    {
        return $this->hasMany(FormSubmission::class, 'form_id');
    }
}
