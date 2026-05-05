<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StripeUrl extends Model
{
    protected $fillable = [
        'assessment_pay',
        'exploration_pay',

        'assessment_pay_ind',
        'exploration_pay_ind',

        'created_by',
        'updated_by',
    ];

}
