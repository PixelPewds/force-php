<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ResourceRemark extends Model
{
    protected $fillable = [
        'resource_id',
        'admin_remark',
        'student_response',
        'responded_at',
        'file_path',
    ];

    protected $casts = [
        'responded_at' => 'datetime',
    ];

    /**
     * Get the resource
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}
