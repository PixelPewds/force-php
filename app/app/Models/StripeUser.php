<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class StripeUser extends Model
{
    use HasFactory;

    protected $table = 'stripe_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_name',
        'relation',
        'education',
        'phone',
        'email',
        'address',
        'address2',
        'city',
        'state',
        'zip',
        'country',
        'student_name',
        'grade',
        'gender',
        'school',
        'board',
        'career_clarity',
        'goals',
        'comments',
        'program',
        'timestamp',
        'payment_status',
        'stripe_session_id',
        'client_reference_id',
        'type'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'goals' => 'array'
    ];
}
