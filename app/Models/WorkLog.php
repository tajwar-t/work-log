<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'log_date',
        'start_time',
        'end_time',
        'work_done',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    protected $casts = [
        'log_date' => 'date', // automatically casts to Carbon
    ];

}
