<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            ExerciseTypeSeeder::class,
            WorkoutSeeder::class,
            WorkoutSessionSeeder::class,
            WorkoutExerciseSeeder::class,
            GoalSeeder::class,
            NutritionLogSeeder::class,
            BodyMeasurementSeeder::class,
            WorkoutPlanSeeder::class,
            PlanExerciseSeeder::class,
        ]);
    }
}
