<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Workout extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'exercise',
        'duration',
        'calories',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'duration' => 'integer',
        'calories' => 'integer',
    ];

    /**
     * Scope a query to only include workouts from recent days.
     *
     * @param Builder $query
     * @param int $days
     * @return Builder
     */
    public function scopeRecent(Builder $query, int $days = 7): Builder
    {
        return $query->where('date', '>=', now()->subDays($days));
    }

    /**
     * Scope a query to filter workouts by exercise name.
     *
     * @param Builder $query
     * @param string $exercise
     * @return Builder
     */
    public function scopeByExercise(Builder $query, string $exercise): Builder
    {
        return $query->where('exercise', 'like', '%' . $exercise . '%');
    }

    /**
     * Scope a query to filter by date range.
     *
     * @param Builder $query
     * @param string $startDate
     * @param string $endDate
     * @return Builder
     */
    public function scopeDateRange(Builder $query, string $startDate, string $endDate): Builder
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope a query to filter by minimum duration.
     *
     * @param Builder $query
     * @param int $minutes
     * @return Builder
     */
    public function scopeMinimumDuration(Builder $query, int $minutes): Builder
    {
        return $query->where('duration', '>=', $minutes);
    }

    /**
     * Get the formatted date attribute.
     *
     * @return string
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->date->format('M d, Y');
    }

    /**
     * Get the duration in hours and minutes format.
     *
     * @return string
     */
    public function getFormattedDurationAttribute(): string
    {
        $hours = floor($this->duration / 60);
        $minutes = $this->duration % 60;
        
        if ($hours > 0) {
            return $hours . 'h ' . $minutes . 'm';
        }
        
        return $minutes . 'm';
    }

    /**
     * Calculate calories per minute for efficiency comparison.
     *
     * @return float|null
     */
    public function getCaloriesPerMinuteAttribute(): ?float
    {
        if (!$this->calories || $this->duration <= 0) {
            return null;
        }
        
        return round($this->calories / $this->duration, 2);
    }
}
