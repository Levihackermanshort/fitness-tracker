<?php

namespace Database\Seeders;

use App\Models\NutritionLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class NutritionLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NutritionLog::truncate();

        $users = User::all();
        $mealTypes = ['breakfast', 'lunch', 'dinner', 'snack', 'other'];
        
        $foods = [
            // Breakfast foods
            ['name' => 'Oatmeal', 'calories' => 150, 'protein' => 5, 'carbs' => 27, 'fat' => 3, 'fiber' => 4, 'sugar' => 1, 'sodium' => 10],
            ['name' => 'Greek Yogurt', 'calories' => 100, 'protein' => 17, 'carbs' => 6, 'fat' => 0, 'fiber' => 0, 'sugar' => 6, 'sodium' => 50],
            ['name' => 'Banana', 'calories' => 105, 'protein' => 1, 'carbs' => 27, 'fat' => 0, 'fiber' => 3, 'sugar' => 14, 'sodium' => 1],
            ['name' => 'Eggs', 'calories' => 140, 'protein' => 12, 'carbs' => 1, 'fat' => 10, 'fiber' => 0, 'sugar' => 1, 'sodium' => 140],
            ['name' => 'Whole Wheat Toast', 'calories' => 80, 'protein' => 4, 'carbs' => 14, 'fat' => 1, 'fiber' => 2, 'sugar' => 2, 'sodium' => 150],

            // Lunch foods
            ['name' => 'Grilled Chicken Breast', 'calories' => 165, 'protein' => 31, 'carbs' => 0, 'fat' => 4, 'fiber' => 0, 'sugar' => 0, 'sodium' => 74],
            ['name' => 'Brown Rice', 'calories' => 220, 'protein' => 5, 'carbs' => 45, 'fat' => 2, 'fiber' => 4, 'sugar' => 1, 'sodium' => 10],
            ['name' => 'Mixed Vegetables', 'calories' => 50, 'protein' => 3, 'carbs' => 10, 'fat' => 0, 'fiber' => 4, 'sugar' => 5, 'sodium' => 20],
            ['name' => 'Salmon Fillet', 'calories' => 206, 'protein' => 22, 'carbs' => 0, 'fat' => 12, 'fiber' => 0, 'sugar' => 0, 'sodium' => 59],
            ['name' => 'Quinoa', 'calories' => 222, 'protein' => 8, 'carbs' => 40, 'fat' => 4, 'fiber' => 5, 'sugar' => 0, 'sodium' => 13],

            // Dinner foods
            ['name' => 'Lean Beef Steak', 'calories' => 250, 'protein' => 26, 'carbs' => 0, 'fat' => 15, 'fiber' => 0, 'sugar' => 0, 'sodium' => 60],
            ['name' => 'Sweet Potato', 'calories' => 112, 'protein' => 2, 'carbs' => 26, 'fat' => 0, 'fiber' => 4, 'sugar' => 5, 'sodium' => 6],
            ['name' => 'Broccoli', 'calories' => 55, 'protein' => 5, 'carbs' => 11, 'fat' => 1, 'fiber' => 5, 'sugar' => 3, 'sodium' => 33],
            ['name' => 'Pasta', 'calories' => 200, 'protein' => 7, 'carbs' => 40, 'fat' => 2, 'fiber' => 2, 'sugar' => 2, 'sodium' => 5],
            ['name' => 'Turkey Breast', 'calories' => 135, 'protein' => 25, 'carbs' => 0, 'fat' => 3, 'fiber' => 0, 'sugar' => 0, 'sodium' => 45],

            // Snacks
            ['name' => 'Almonds', 'calories' => 164, 'protein' => 6, 'carbs' => 6, 'fat' => 14, 'fiber' => 4, 'sugar' => 1, 'sodium' => 1],
            ['name' => 'Apple', 'calories' => 95, 'protein' => 0, 'carbs' => 25, 'fat' => 0, 'fiber' => 4, 'sugar' => 19, 'sodium' => 2],
            ['name' => 'Protein Bar', 'calories' => 200, 'protein' => 20, 'carbs' => 15, 'fat' => 8, 'fiber' => 3, 'sugar' => 8, 'sodium' => 200],
            ['name' => 'Hummus', 'calories' => 25, 'protein' => 1, 'carbs' => 2, 'fat' => 2, 'fiber' => 1, 'sugar' => 0, 'sodium' => 60],
            ['name' => 'Carrot Sticks', 'calories' => 25, 'protein' => 1, 'carbs' => 6, 'fat' => 0, 'fiber' => 2, 'sugar' => 3, 'sodium' => 50],

            // Pre/Post workout
            ['name' => 'Banana', 'calories' => 105, 'protein' => 1, 'carbs' => 27, 'fat' => 0, 'fiber' => 3, 'sugar' => 14, 'sodium' => 1],
            ['name' => 'Protein Shake', 'calories' => 120, 'protein' => 25, 'carbs' => 3, 'fat' => 1, 'fiber' => 0, 'sugar' => 2, 'sodium' => 100],
            ['name' => 'Energy Bar', 'calories' => 150, 'protein' => 5, 'carbs' => 25, 'fat' => 4, 'fiber' => 2, 'sugar' => 12, 'sodium' => 150],
        ];

        $nutritionLogs = [];

        foreach ($users as $user) {
            // Create nutrition logs for the last 30 days
            for ($day = 0; $day < 30; $day++) {
                $date = now()->subDays($day);
                
                // 2-4 meals per day
                $mealCount = rand(2, 4);
                $mealsForDay = array_slice($mealTypes, 0, $mealCount);
                
                foreach ($mealsForDay as $mealType) {
                    // 1-3 food items per meal
                    $foodCount = rand(1, 3);
                    
                    for ($f = 0; $f < $foodCount; $f++) {
                        $food = $foods[array_rand($foods)];
                        $quantity = rand(50, 200) / 100; // 0.5 to 2.0 servings
                        
                        $nutritionLogs[] = [
                            'user_id' => $user->id,
                            'date' => $date->toDateString(),
                            'meal_type' => $mealType,
                            'food_name' => $food['name'],
                            'quantity' => $quantity,
                            'unit' => 'serving',
                            'calories' => round($food['calories'] * $quantity, 1),
                            'protein_g' => round($food['protein'] * $quantity, 1),
                            'carbs_g' => round($food['carbs'] * $quantity, 1),
                            'fat_g' => round($food['fat'] * $quantity, 1),
                            'fiber_g' => round($food['fiber'] * $quantity, 1),
                            'sugar_g' => round($food['sugar'] * $quantity, 1),
                            'notes' => $this->getRandomNotes(),
                            'created_at' => $date,
                            'updated_at' => $date,
                        ];
                    }
                }
            }
        }

        foreach ($nutritionLogs as $log) {
            NutritionLog::create($log);
        }

        $this->command->info('Successfully seeded ' . count($nutritionLogs) . ' nutrition logs!');
    }

    private function getRandomNotes(): ?string
    {
        $notes = [
            'Delicious meal!',
            'Felt satisfied after this.',
            'Could use more seasoning.',
            'Great portion size.',
            'Will have this again.',
            'Perfect for pre-workout.',
            'Good post-workout meal.',
            'Felt energized after eating.',
            'Need to add more vegetables.',
            'Very filling.',
        ];

        return rand(0, 4) === 0 ? $notes[array_rand($notes)] : null;
    }
}
