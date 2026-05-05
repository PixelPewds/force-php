<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = [
        'question_id',
        'option_text',
        'order',
        'created_by',
        'updated_by',
    ];

    public function getAnswerTextAttribute()
    {
        $answer = $this->answer;
        if (is_string($answer) && str_starts_with($answer, '[')) {
            $ids = json_decode($answer, true);
            return QuestionOption::whereIn('id', $ids)
                ->pluck('option_text')
                ->toArray();
        }
        if (is_numeric($answer)) {
            return optional($this->option)->option_text;
        }
        return $answer;
    }
}
