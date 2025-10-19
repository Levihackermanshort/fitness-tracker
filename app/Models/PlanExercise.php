<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlanExercise extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'workout_plan_id',
        'exercise_type_id',
        'day_of_week',
        'week_number',
        'order_in_day',
        'sets',
        'reps',
        'weight_kg',
        'duration_seconds',
        'rest_time_seconds',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'day_of_week' => 'integer',
        'week_number' => 'integer',
        'order_in_day' => 'integer',
        'sets' => 'integer',
        'reps' => 'integer',
        'weight_kg' => 'decimal:2',
        'duration_seconds' => 'integer',
        'rest_time_seconds' => 'integer',
    ];

    /**
     * Get the workout plan that owns the plan exercise.
     */
    public function workoutPlan(): BelongsTo
    {
        return $this->belongsTo(WorkoutPlan::class);
    }

    /**
     * Get the exercise type.
     */
    public function exerciseType(): BelongsTo
    {
        return $this->belongsTo(ExerciseType::class);
    }

    /**
     * Get the day name.
     */
    public function getDayNameAttribute(): string
    {
        $days = [
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
            7 => 'Sunday',
        ];
        
        return $days[$this->day_of_week] ?? 'Unknown';
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_seconds) {
            return '0s';
        }
        
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        
        if ($minutes > 0) {
            return $minutes . 'm ' . $seconds . 's';
        }
        
        return $seconds . 's';
    }

    /**
     * Get the formatted rest time.
     */
    public function getFormattedRestTimeAttribute(): string
    {
        if (!$this->rest_time_seconds) {
            return '0s';
        }
        
        $minutes = floor($this->rest_time_seconds / 60);
        $seconds = $this->rest_time_seconds % 60;
        
        if ($minutes > 0) {
            return $minutes . 'm ' . $seconds . 's';
        }
        
        return $seconds . 's';
    }
}
