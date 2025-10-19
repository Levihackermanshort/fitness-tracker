<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutSession extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'date',
        'start_time',
        'end_time',
        'total_duration',
        'total_calories',
        'notes',
        'mood_before',
        'mood_after',
        'perceived_exertion',
        'weather_conditions',
        'location',
        'workout_type',
        'is_completed',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'total_duration' => 'integer',
        'total_calories' => 'integer',
        'mood_before' => 'integer',
        'mood_after' => 'integer',
        'perceived_exertion' => 'integer',
        'is_completed' => 'boolean',
    ];

    /**
     * Get the user that owns the workout session.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the workout exercises for the session.
     */
    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    /**
     * Scope a query to only include completed workouts.
     */
    public function scopeCompleted($query)
    {
        return $query->where('is_completed', true);
    }

    /**
     * Scope a query to filter by workout type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('workout_type', $type);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Get the mood improvement (after - before).
     */
    public function getMoodImprovementAttribute(): ?int
    {
        if ($this->mood_before === null || $this->mood_after === null) {
            return null;
        }
        
        return $this->mood_after - $this->mood_before;
    }

    /**
     * Get the formatted duration.
     */
    public function getFormattedDurationAttribute(): string
    {
        if (!$this->total_duration) {
            return '0m';
        }
        
        $hours = floor($this->total_duration / 60);
        $minutes = $this->total_duration % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        
        return $minutes . 'm';
    }

    /**
     * Calculate calories per minute.
     */
    public function getCaloriesPerMinuteAttribute(): ?float
    {
        if (!$this->total_calories || !$this->total_duration || $this->total_duration <= 0) {
            return null;
        }
        
        return round($this->total_calories / $this->total_duration, 2);
    }
}
