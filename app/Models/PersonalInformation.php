<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalInformation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'age',
        'gender',
        'weight',
        'height',
        'activity_level',
        'max_calories',
    ];

    protected static function booted()
    {
        static::saving(function ($info) {
            $bmr = 0;
            if ($info->gender === 'male') {
                $bmr = 10 * $info->weight + 6.25 * $info->height - 5 * $info->age + 5;
            } else {
                $bmr = 10 * $info->weight + 6.25 * $info->height - 5 * $info->age - 161;
            }

            $activityFactors = [
                1 => 1.2,    // sedentary
                2 => 1.375,  // ringan
                3 => 1.55,   // sedang
                4 => 1.725,  // berat
                5 => 1.9     // sangat berat
            ];

            $factor = $activityFactors[$info->activity_level] ?? 1.2;
            $info->max_calories = round($bmr * $factor);
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
