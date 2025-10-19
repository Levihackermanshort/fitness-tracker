<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Goal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'goal_type',
        'target_value',
        'current_value',
        'unit',
        'start_date',
        'target_date',
        'is_achieved',
        'achieved_date',
        'priority',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'target_value' => 'decimal:2',
        'current_value' => 'decimal:2',
        'start_date' => 'date',
        'target_date' => 'date',
        'achieved_date' => 'date',
        'is_achieved' => 'boolean',
        'priority' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Get the user that owns the goal.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to only include active goals.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include achieved goals.
     */
    public function scopeAchieved($query)
    {
        return $query->where('is_achieved', true);
    }

    /**
     * Scope a query to filter by goal type.
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('goal_type', $type);
    }

    /**
     * Calculate progress percentage.
     */
    public function getProgressPercentageAttribute(): ?float
    {
        if (!$this->target_value || $this->target_value == 0) {
            return null;
        }
        
        return round(($this->current_value / $this->target_value) * 100, 2);
    }

    /**
     * Get the remaining value to achieve the goal.
     */
    public function getRemainingValueAttribute(): ?float
    {
        if (!$this->target_value || !$this->current_value) {
            return null;
        }
        
        return max(0, $this->target_value - $this->current_value);
    }

    /**
     * Check if goal is overdue.
     */
    public function getIsOverdueAttribute(): bool
    {
        if (!$this->target_date || $this->is_achieved) {
            return false;
        }
        
        return $this->target_date < now()->toDateString();
    }

    /**
     * Get goal status.
     */
    public function getStatusAttribute(): string
    {
        if ($this->is_achieved) {
            return 'Achieved';
        }
        
        if ($this->is_overdue) {
            return 'Overdue';
        }
        
        if ($this->target_date && $this->target_date <= now()->addDays(30)->toDateString()) {
            return 'Due Soon';
        }
        
        return 'On Track';
    }
}
