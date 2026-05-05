<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'form_id',
        'name',
        'step_number',
        'description',
        'created_by',
        'updated_by',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('step_number');
    }
}
