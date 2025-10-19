<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BodyMeasurement extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'measurement_date',
        'weight_kg',
        'body_fat_percentage',
        'muscle_mass_kg',
        'chest_cm',
        'waist_cm',
        'hips_cm',
        'thigh_cm',
        'arm_cm',
        'neck_cm',
        'bmi',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'measurement_date' => 'date',
        'weight_kg' => 'decimal:2',
        'body_fat_percentage' => 'decimal:2',
        'muscle_mass_kg' => 'decimal:2',
        'chest_cm' => 'decimal:2',
        'waist_cm' => 'decimal:2',
        'hips_cm' => 'decimal:2',
        'thigh_cm' => 'decimal:2',
        'arm_cm' => 'decimal:2',
        'neck_cm' => 'decimal:2',
        'bmi' => 'decimal:2',
    ];

    /**
     * Get the user that owns the body measurement.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('measurement_date', [$startDate, $endDate]);
    }

    /**
     * Get the latest measurement for a user.
     */
    public static function getLatest($userId)
    {
        return self::where('user_id', $userId)
            ->orderBy('measurement_date', 'desc')
            ->first();
    }

    /**
     * Calculate BMI if not provided.
     */
    public function calculateBmi(): ?float
    {
        if (!$this->weight_kg || !$this->user->height_cm) {
            return null;
        }
        
        $heightInMeters = $this->user->height_cm / 100;
        return round($this->weight_kg / ($heightInMeters * $heightInMeters), 2);
    }

    /**
     * Get BMI category.
     */
    public function getBmiCategoryAttribute(): string
    {
        if (!$this->bmi) {
            return 'Unknown';
        }
        
        if ($this->bmi < 18.5) {
            return 'Underweight';
        } elseif ($this->bmi < 25) {
            return 'Normal';
        } elseif ($this->bmi < 30) {
            return 'Overweight';
        } else {
            return 'Obese';
        }
    }

    /**
     * Get weight change from previous measurement.
     */
    public function getWeightChangeAttribute(): ?float
    {
        $previousMeasurement = self::where('user_id', $this->user_id)
            ->where('measurement_date', '<', $this->measurement_date)
            ->orderBy('measurement_date', 'desc')
            ->first();
        
        if (!$previousMeasurement || !$this->weight_kg) {
            return null;
        }
        
        return round($this->weight_kg - $previousMeasurement->weight_kg, 2);
    }
}
