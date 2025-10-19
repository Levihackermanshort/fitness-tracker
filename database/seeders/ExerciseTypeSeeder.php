<?php

namespace Database\Seeders;

use App\Models\ExerciseType;
use Illuminate\Database\Seeder;

class ExerciseTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ExerciseType::truncate();

        $exercises = [
            [
                'name' => 'Push-ups',
                'category' => 'strength',
                'muscle_groups' => 'chest',
                'equipment_needed' => 'none',
                'difficulty_level' => 'beginner',
                'estimated_calories_per_minute' => 5,
                'description' => 'Classic bodyweight exercise for chest and arms.',
                'instructions' => 'Start in plank position, lower chest to ground, push back up.',
                'is_active' => true,
            ],
            [
                'name' => 'Running',
                'category' => 'cardio',
                'muscle_groups' => 'legs',
                'equipment_needed' => 'none',
                'difficulty_level' => 'intermediate',
                'estimated_calories_per_minute' => 10,
                'description' => 'Cardiovascular exercise for endurance.',
                'instructions' => 'Maintain steady pace, land on forefoot.',
                'is_active' => true,
            ],
            [
                'name' => 'Squats',
                'category' => 'strength',
                'muscle_groups' => 'legs',
                'equipment_needed' => 'none',
                'difficulty_level' => 'beginner',
                'estimated_calories_per_minute' => 6,
                'description' => 'Lower body strength exercise.',
                'instructions' => 'Stand with feet shoulder-width apart, lower hips back and down.',
                'is_active' => true,
            ],
        ];

        foreach ($exercises as $exercise) {
            ExerciseType::create($exercise);
        }

        $this->command->info('Successfully seeded ' . count($exercises) . ' exercise types!');
    }
}