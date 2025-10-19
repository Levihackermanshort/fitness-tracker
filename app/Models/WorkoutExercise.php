<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkoutExercise extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'workout_session_id',
        'exercise_type_id',
        'exercise_name',
        'order_in_workout',
        'sets',
        'reps',
        'weight_kg',
        'duration_seconds',
        'distance_meters',
        'calories_burned',
        'rest_time_seconds',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order_in_workout' => 'integer',
        'sets' => 'integer',
        'reps' => 'integer',
        'weight_kg' => 'decimal:2',
        'duration_seconds' => 'integer',
        'distance_meters' => 'decimal:2',
        'calories_burned' => 'integer',
        'rest_time_seconds' => 'integer',
    ];

    /**
     * Get the workout session that owns the exercise.
     */
    public function workoutSession(): BelongsTo
    {
        return $this->belongsTo(WorkoutSession::class);
    }

    /**
     * Get the exercise type.
     */
    public function exerciseType(): BelongsTo
    {
        return $this->belongsTo(ExerciseType::class);
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

    /**
     * Calculate total volume (sets × reps × weight).
     */
    public function getTotalVolumeAttribute(): ?float
    {
        if (!$this->sets || !$this->reps || !$this->weight_kg) {
            return null;
        }
        
        return $this->sets * $this->reps * $this->weight_kg;
    }
}
