<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskCompletionRemark extends Model
{
    protected $fillable = [
        'task_completion_id',
        'question_id',
        'admin_remark',
        'student_response',
        'responded_at',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Get the task completion system
     */
    public function taskCompletion(): BelongsTo
    {
        return $this->belongsTo(TaskCompletionSystem::class);
    }

    /**
     * Get the question
     */
    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
