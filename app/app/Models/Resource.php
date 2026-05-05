<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    protected $fillable = [
        'student_id',
        'assigned_by',
        'resource_type',
        'title',
        'description',
        'file_path',
        'resource_url',
        'assigned_at',
        'deadline',
        'accessed_at',
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
        'accessed_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the student assigned to this resource
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Get the admin who assigned this resource
     */
    public function assignedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }

    /**
     * Get the remarks for this resource
     */
    public function remarks(): HasMany
    {
        return $this->hasMany(ResourceRemark::class);
    }

    /**
     * Check if deadline is overdue
     */
    public function isOverdue(): bool
    {
        return $this->deadline && $this->deadline < now()->toDateString() && $this->status !== 'completed';
    }

    /**
     * Get days remaining until deadline
     */
    public function daysRemaining(): int
    {
        if (!$this->deadline) {
            return 0;
        }
        return now()->diffInDays($this->deadline, false);
    }

    /**
     * Get formatted deadline status
     */
    public function getDeadlineStatus(): string
    {
        if (!$this->deadline) {
            return 'No deadline';
        }

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

    /**
     * Get resource type label
     */
    public function getResourceTypeLabel(): string
    {
        return match ($this->resource_type) {
            'pdf' => 'PDF Document',
            'video' => 'Video',
            'article' => 'Article',
            'workbook' => 'Workbook',
            'media' => 'Media',
            default => ucfirst($this->resource_type)
        };
    }
}
