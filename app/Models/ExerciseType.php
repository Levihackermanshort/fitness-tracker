<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ExerciseType extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'category',
        'muscle_groups',
        'equipment_needed',
        'difficulty_level',
        'calories_per_minute',
        'description',
        'instructions',
        'video_url',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'muscle_groups' => 'array',
        'difficulty_level' => 'integer',
        'calories_per_minute' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the workout exercises for this exercise type.
     */
    public function workoutExercises(): HasMany
    {
        return $this->hasMany(WorkoutExercise::class);
    }

    /**
     * Get the plan exercises for this exercise type.
     */
    public function planExercises(): HasMany
    {
        return $this->hasMany(PlanExercise::class);
    }

    /**
     * Scope a query to only include active exercises.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to filter by category.
     */
    public function scopeByCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope a query to filter by difficulty level.
     */
    public function scopeByDifficulty($query, int $level)
    {
        return $query->where('difficulty_level', $level);
    }
}
