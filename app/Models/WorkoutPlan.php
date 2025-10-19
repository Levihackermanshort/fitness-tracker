<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkoutPlan extends Model
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
        'description',
        'plan_type',
        'difficulty_level',
        'duration_weeks',
        'frequency_per_week',
        'is_active',
        'is_template',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'difficulty_level' => 'integer',
        'duration_weeks' => 'integer',
        'frequency_per_week' => 'integer',
        'is_active' => 'boolean',
        'is_template' => 'boolean',
    ];

    /**
     * Get the user that owns the workout plan.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the plan exercises for the workout plan.
     */
    public function planExercises(): HasMany
    {
        return $this->hasMany(PlanExercise::class);
    }

    /**
     * Scope a query to only include active plans.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include templates.
     */
    public function scopeTemplates($query)
    {
        return $query->where('is_template', true);
    }

    /**
     * Scope a query to filter by plan type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('plan_type', $type);
    }

    /**
     * Get exercises for a specific day and week.
     */
    public function getExercisesForDay(int $dayOfWeek, int $weekNumber = 1)
    {
        return $this->planExercises()
            ->where('day_of_week', $dayOfWeek)
            ->where('week_number', $weekNumber)
            ->orderBy('order_in_day')
            ->get();
    }

    /**
     * Get all exercises grouped by day and week.
     */
    public function getExercisesGrouped()
    {
        return $this->planExercises()
            ->orderBy('week_number')
            ->orderBy('day_of_week')
            ->orderBy('order_in_day')
            ->get()
            ->groupBy(['week_number', 'day_of_week']);
    }
}
