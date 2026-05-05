<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'section_id',
        'question_text',
        'type',
        'is_required',
        'is_other',
        'order',
        'range_number',
        'has_image',
        'form_id',
        'created_by',
        'updated_by',
        'has_image'
    ];

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }
    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }
}
