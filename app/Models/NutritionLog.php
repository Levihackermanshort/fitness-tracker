<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NutritionLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'nutrition_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'date',
        'meal_type',
        'food_name',
        'quantity',
        'unit',
        'calories',
        'protein_g',
        'carbs_g',
        'fat_g',
        'fiber_g',
        'sugar_g',
        'sodium_mg',
        'notes',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'quantity' => 'decimal:2',
        'calories' => 'decimal:2',
        'protein_g' => 'decimal:2',
        'carbs_g' => 'decimal:2',
        'fat_g' => 'decimal:2',
        'fiber_g' => 'decimal:2',
        'sugar_g' => 'decimal:2',
        'sodium_mg' => 'decimal:2',
    ];

    /**
     * Get the user that owns the nutrition log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope a query to filter by meal type.
     */
    public function scopeByMealType($query, string $mealType)
    {
        return $query->where('meal_type', $mealType);
    }

    /**
     * Scope a query to filter by date range.
     */
    public function scopeDateRange($query, string $startDate, string $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Calculate total macronutrients for the day.
     */
    public static function getDailyTotals($userId, $date)
    {
        return self::where('user_id', $userId)
            ->where('date', $date)
            ->selectRaw('
                SUM(calories) as total_calories,
                SUM(protein_g) as total_protein,
                SUM(carbs_g) as total_carbs,
                SUM(fat_g) as total_fat,
                SUM(fiber_g) as total_fiber,
                SUM(sugar_g) as total_sugar,
                SUM(sodium_mg) as total_sodium
            ')
            ->first();
    }

    /**
     * Get the meal type options.
     */
    public static function getMealTypes(): array
    {
        return [
            'breakfast' => 'Breakfast',
            'lunch' => 'Lunch',
            'dinner' => 'Dinner',
            'snack' => 'Snack',
            'pre_workout' => 'Pre-Workout',
            'post_workout' => 'Post-Workout',
        ];
    }
}
