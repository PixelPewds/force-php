<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'form_id',
        'student_id',
        'status',
        'current_step',
        'created_by',
        'updated_by'
    ];

    public function responses()
    {
        return $this->hasMany(Response::class, 'submission_id');
    }

    public function TaskCompletionSystem()
    {
        return $this->hasMany(TaskCompletionSystem::class, 'form_id');
    }

    public function user()
    {
        return $this->hasMany(User::class);
    }

    public function form()
    {
        return $this->belongsTo(Form::class);
    }
}