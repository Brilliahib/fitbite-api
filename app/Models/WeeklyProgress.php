<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyProgress extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'weight_start',
        'weight_end',
        'progress_percentage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
