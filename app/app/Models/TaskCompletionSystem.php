<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaskCompletionSystem extends Model
{
    protected $fillable = [
        'student_id',
        'form_id',
        'assigned_by',
        'assigned_at',
        'deadline',
        'submitted_at',
        'completed_at',
        'status',
        'completion_percentage',
        'admin_remarks',
        'overall_remarks',
        'student_response_to_remarks',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'deadline' => 'date',
        'submitted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the student assigned to this task
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the admin who assigned this task
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get the form for this task
     */
    public function form(): BelongsTo
    {
        return $this->belongsTo(Form::class);
    }

    public function submissions(): BelongsTo
    {
        return $this->belongsTo(FormSubmission::class, 'form_id');
    }

    public function response()
    {
        return $this->hasMany(Response::class, 'form_id');
    }

    /**
     * Get the remarks for each question
     */
    public function questionRemarks(): HasMany
    {
        return $this->hasMany(TaskCompletionRemark::class, 'task_completion_id');
    }

    /**
     * Check if deadline is overdue
     */
    public function isOverdue(): bool
    {
        return $this->deadline < now()->toDateString() && $this->status !== 'completed';
    }

    /**
     * Get days remaining until deadline
     */
    public function daysRemaining(): int
    {
        return now()->diffInDays($this->deadline, false);
    }

    /**
     * Get formatted deadline status
     */
    public function getDeadlineStatus(): string
    {
        if ($this->isOverdue()) {
            return 'Overdue';
        }
        $days = $this->daysRemaining();
        if ($days < 0) {
            return abs($days) . ' days overdue';
        }
        if ($days === 0) {
            return 'Due today';
        }
        return $days . ' days remaining';
    }
}

