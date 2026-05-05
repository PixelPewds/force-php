<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Response extends Model
{
    protected $fillable = [
        'submission_id',
        'question_id',
        'answer',
        'option_id',
        'checkbox_id',
        'other_option'
    ];

    // public function question()
    // {
    //     return $this->belongsTo(Question::class, 'question_id');
    // }
    // public function option()
    // {
    //     return $this->belongsTo(QuestionOption::class, 'answer');
    // }

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(QuestionOption::class);
    }

    public function getCheckboxOptionsAttribute()
    {
        if ($this->question->type !== 'checkbox')
            return [];

        $ids = json_decode($this->answer, true) ?? [];

        $learningData = \App\Models\QuestionOption::whereIn('id', $ids)
            ->pluck('option_text');

        $result = $learningData->map(function ($option) {
            return (int) trim(explode('.', $option)[0] ?? "0");
        })->values()->toArray();

        return $result;
    }

    public function getFormattedAnswerAttribute()
    {
        $ids = json_decode($this->answer ?? '[]', true);
        if (empty($ids)) {
            return '';
        }
        return \App\Models\QuestionOption::whereIn('id', $ids)
            ->pluck('option_text')
            ->implode(', ');
    }

    public function responses()
    {
        return $this->hasMany(Response::class, 'form_submission_id');
    }
}

