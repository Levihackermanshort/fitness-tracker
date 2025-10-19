<?php

namespace Database\Seeders;

use App\Models\PlanExercise;
use App\Models\WorkoutPlan;
use App\Models\ExerciseType;
use Illuminate\Database\Seeder;

class PlanExerciseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PlanExercise::truncate();

        $workoutPlans = WorkoutPlan::all();
        $exerciseTypes = ExerciseType::all();

        $planExercises = [];

        foreach ($workoutPlans as $plan) {
            // Determine exercises based on plan type
            $suitableExercises = $this->getSuitableExercises($plan->plan_type, $exerciseTypes);
            
            // Create exercises for each week and day
            for ($week = 1; $week <= $plan->duration_weeks; $week++) {
                for ($day = 1; $day <= $plan->frequency_per_week; $day++) {
                    // 3-6 exercises per workout day
                    $exerciseCount = rand(3, 6);
                    $dayExercises = array_slice($suitableExercises, 0, $exerciseCount);
                    
                    foreach ($dayExercises as $index => $exerciseType) {
                        $exerciseData = $this->generateExerciseData($exerciseType, $plan->difficulty_level);
                        
                        $planExercises[] = [
                            'workout_plan_id' => $plan->id,
                            'exercise_type_id' => $exerciseType->id,
                            'day_of_week' => $day,
                            'week_number' => $week,
                            'order_in_day' => $index + 1,
                            'sets' => $exerciseData['sets'],
                            'reps' => $exerciseData['reps'],
                            'weight_kg' => $exerciseData['weight'],
                            'duration_seconds' => $exerciseData['duration'],
                            'rest_time_seconds' => $exerciseData['rest_time'],
                            'notes' => $exerciseData['notes'],
                            'created_at' => $plan->created_at,
                        ];
                    }
                }
            }
        }

        foreach ($planExercises as $exercise) {
            PlanExercise::create($exercise);
        }

        $this->command->info('Successfully seeded ' . count($planExercises) . ' plan exercises!');
    }

    private function getSuitableExercises(string $planType, $exerciseTypes)
    {
        $suitableExercises = $exerciseTypes->filter(function ($exercise) use ($planType) {
            return $exercise->category === $planType || 
                   ($planType === 'hybrid' && in_array($exercise->category, ['strength', 'cardio'])) ||
                   ($planType === 'sport_specific' && in_array($exercise->category, ['strength', 'cardio', 'sports'])) ||
                   ($planType === 'rehabilitation' && in_array($exercise->category, ['flexibility', 'bodyweight']));
        });

        // If no suitable exercises found, use all exercises
        if ($suitableExercises->isEmpty()) {
            $suitableExercises = $exerciseTypes;
        }

        return $suitableExercises->shuffle();
    }

    private function generateExerciseData($exerciseType, int $difficultyLevel): array
    {
        $baseData = [
            'sets' => null,
            'reps' => null,
            'weight' => null,
            'duration' => null,
            'rest_time' => null,
            'notes' => null,
        ];

        switch ($exerciseType->category) {
            case 'strength':
                $baseData['sets'] = rand(3, 5) + ($difficultyLevel - 1);
                $baseData['reps'] = max(5, 15 - $difficultyLevel);
                $baseData['weight'] = rand(20, 100) + ($difficultyLevel * 10);
                $baseData['rest_time'] = rand(60, 180);
                $baseData['notes'] = $this->getStrengthNotes();
                break;

            case 'cardio':
                $baseData['duration'] = rand(300, 1200) + ($difficultyLevel * 120);
                $baseData['rest_time'] = rand(30, 120);
                $baseData['notes'] = $this->getCardioNotes();
                break;

            case 'bodyweight':
                $baseData['sets'] = rand(2, 4) + ($difficultyLevel - 1);
                $baseData['reps'] = max(5, 20 - $difficultyLevel);
                $baseData['rest_time'] = rand(30, 90);
                $baseData['notes'] = $this->getBodyweightNotes();
                break;

            case 'flexibility':
                $baseData['duration'] = rand(300, 600);
                $baseData['notes'] = $this->getFlexibilityNotes();
                break;

            case 'sports':
                $baseData['duration'] = rand(600, 1800);
                $baseData['rest_time'] = rand(60, 300);
                $baseData['notes'] = $this->getSportsNotes();
                break;

            default:
                $baseData['sets'] = rand(2, 4);
                $baseData['reps'] = rand(8, 15);
                $baseData['duration'] = rand(300, 600);
                $baseData['rest_time'] = rand(30, 90);
                break;
        }

        return $baseData;
    }

    private function getStrengthNotes(): ?string
    {
        $notes = [
            'Focus on proper form and controlled movement.',
            'Increase weight gradually each week.',
            'Maintain tension throughout the movement.',
            'Keep core engaged during the exercise.',
            'Focus on the eccentric (lowering) phase.',
        ];
        return rand(0, 2) === 0 ? $notes[array_rand($notes)] : null;
    }

    private function getCardioNotes(): ?string
    {
        $notes = [
            'Maintain steady pace throughout.',
            'Focus on breathing rhythm.',
            'Keep heart rate in target zone.',
            'Stay hydrated during exercise.',
            'Listen to your body and adjust intensity.',
        ];
        return rand(0, 2) === 0 ? $notes[array_rand($notes)] : null;
    }

    private function getBodyweightNotes(): ?string
    {
        $notes = [
            'Use full range of motion.',
            'Control the movement, avoid momentum.',
            'Engage core throughout the exercise.',
            'Modify difficulty as needed.',
            'Focus on quality over quantity.',
        ];
        return rand(0, 2) === 0 ? $notes[array_rand($notes)] : null;
    }

    private function getFlexibilityNotes(): ?string
    {
        $notes = [
            'Hold stretch for 30-60 seconds.',
            'Breathe deeply and relax into the stretch.',
            'Avoid bouncing or jerky movements.',
            'Focus on areas that feel tight.',
            'Stretch both sides equally.',
        ];
        return rand(0, 2) === 0 ? $notes[array_rand($notes)] : null;
    }

    private function getSportsNotes(): ?string
    {
        $notes = [
            'Focus on sport-specific movements.',
            'Practice proper technique.',
            'Work on agility and coordination.',
            'Include game-like scenarios.',
            'Focus on both offense and defense.',
        ];
        return rand(0, 2) === 0 ? $notes[array_rand($notes)] : null;
    }
}
