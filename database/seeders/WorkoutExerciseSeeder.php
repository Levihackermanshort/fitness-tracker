<?php

namespace Database\Seeders;

use App\Models\WorkoutExercise;
use App\Models\WorkoutSession;
use App\Models\ExerciseType;
use Illuminate\Database\Seeder;

class WorkoutExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkoutExercise::truncate();

        $workoutSessions = WorkoutSession::all();
        $exerciseTypes = ExerciseType::all();

        $workoutExercises = [];

        foreach ($workoutSessions as $session) {
            // Add 3-8 exercises per workout session
            $exerciseCount = rand(3, 8);
            
            for ($i = 0; $i < $exerciseCount; $i++) {
                $exerciseType = $exerciseTypes->random();
                $orderInWorkout = $i + 1;
                
                // Determine exercise parameters based on type
                $sets = null;
                $reps = null;
                $weight = null;
                $duration = null;
                $distance = null;
                $calories = null;
                $restTime = null;

                if (in_array($exerciseType->category, ['strength', 'bodyweight'])) {
                    $sets = rand(2, 5);
                    $reps = rand(5, 20);
                    if ($exerciseType->category === 'strength') {
                        $weight = rand(20, 150) + (rand(0, 9) / 10); // 20-150.9 kg
                    }
                    $restTime = rand(30, 180); // 30-180 seconds
                } elseif (in_array($exerciseType->category, ['cardio', 'sports'])) {
                    $duration = rand(300, 1800); // 5-30 minutes
                    if ($exerciseType->name === 'Running' || $exerciseType->name === 'Cycling') {
                        $distance = rand(1000, 10000) + (rand(0, 99) / 100); // 1-10 km
                    }
                    $calories = rand(50, 300);
                } elseif ($exerciseType->category === 'flexibility') {
                    $duration = rand(300, 900); // 5-15 minutes
                    $calories = rand(20, 100);
                }

                $workoutExercises[] = [
                    'workout_session_id' => $session->id,
                    'exercise_type_id' => $exerciseType->id,
                    'order_in_workout' => $orderInWorkout,
                    'sets' => $sets,
                    'reps' => $reps,
                    'weight_kg' => $weight,
                    'duration_seconds' => $duration,
                    'distance_meters' => $distance,
                    'calories_burned' => $calories,
                    'notes' => $this->getRandomExerciseNotes($exerciseType),
                ];
            }
        }

        foreach ($workoutExercises as $exercise) {
            WorkoutExercise::create($exercise);
        }

        $this->command->info('Successfully seeded ' . count($workoutExercises) . ' workout exercises!');
    }

    private function getRandomExerciseNotes($exerciseType): ?string
    {
        $notes = [
            'Felt strong on this exercise.',
            'Need to increase weight next time.',
            'Good form maintained throughout.',
            'Felt challenging but doable.',
            'Need to work on technique.',
            'Great pump in the muscles.',
            'Felt easier than last time.',
            'Need more rest between sets.',
            'Excellent range of motion.',
            'Felt fatigued by this point.',
        ];

        return rand(0, 4) === 0 ? $notes[array_rand($notes)] : null;
    }
}
