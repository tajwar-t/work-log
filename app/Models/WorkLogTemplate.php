<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLogTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'template_type',
        'log_date',
        'content',
        'day_start_last_day',
        'day_start_today',
        'day_end_today',
        'day_end_tomorrow',
    ];
}
