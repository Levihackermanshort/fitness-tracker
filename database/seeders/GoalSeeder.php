<?php

namespace Database\Seeders;

use App\Models\Goal;
use App\Models\User;
use Illuminate\Database\Seeder;

class GoalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Goal::truncate();

        $users = User::all();
        $goalTypes = ['weight_loss', 'muscle_gain', 'endurance', 'strength', 'flexibility', 'general_fitness', 'sports_performance', 'other'];
        
        $goals = [];

        foreach ($users as $user) {
            // Create 2-5 goals per user
            $goalCount = rand(2, 5);
            
            for ($i = 0; $i < $goalCount; $i++) {
                $goalType = $goalTypes[array_rand($goalTypes)];
                $startDate = now()->subDays(rand(1, 60));
                $targetDate = $startDate->copy()->addDays(rand(30, 180));
                
                $goalData = $this->generateGoalData($goalType, $user);
                
                $goals[] = [
                    'user_id' => $user->id,
                    'title' => $goalData['title'],
                    'description' => $goalData['description'],
                    'goal_type' => $goalType,
                    'target_value' => $goalData['target_value'],
                    'target_unit' => $goalData['unit'],
                    'current_value' => $goalData['current_value'],
                    'target_date' => $targetDate->toDateString(),
                    'is_achieved' => $goalData['is_achieved'],
                    'achieved_date' => $goalData['achieved_date'],
                    'priority' => rand(1, 5),
                    'is_active' => rand(0, 10) < 8, // 80% active
                    'notes' => $goalData['notes'] ?? null,
                ];
            }
        }

        foreach ($goals as $goal) {
            Goal::create($goal);
        }

        $this->command->info('Successfully seeded ' . count($goals) . ' goals!');
    }

    private function generateGoalData(string $goalType, $user): array
    {
        switch ($goalType) {
            case 'weight_loss':
                $currentWeight = $user->weight_kg ?? 70;
                $targetWeight = $currentWeight - rand(5, 15);
                return [
                    'title' => 'Weight Loss Goal',
                    'description' => 'Achieve target body weight through consistent exercise and nutrition.',
                    'target_value' => $targetWeight,
                    'current_value' => $currentWeight,
                    'unit' => 'kg',
                    'is_achieved' => rand(0, 10) < 3,
                    'achieved_date' => rand(0, 10) < 3 ? now()->subDays(rand(1, 30)) : null,
                ];

            case 'strength':
                $exercises = ['Bench Press', 'Squat', 'Deadlift', 'Overhead Press'];
                $exercise = $exercises[array_rand($exercises)];
                $targetWeight = rand(80, 200);
                return [
                    'title' => "Increase {$exercise} Strength",
                    'description' => "Improve maximum weight for {$exercise} exercise.",
                    'target_value' => $targetWeight,
                    'current_value' => $targetWeight - rand(10, 30),
                    'unit' => 'kg',
                    'is_achieved' => rand(0, 10) < 2,
                    'achieved_date' => rand(0, 10) < 2 ? now()->subDays(rand(1, 30)) : null,
                ];

            case 'endurance':
                $targetTime = rand(20, 60);
                return [
                    'title' => 'Improve Running Endurance',
                    'description' => 'Run continuously for target duration without stopping.',
                    'target_value' => $targetTime,
                    'current_value' => $targetTime - rand(5, 15),
                    'unit' => 'minutes',
                    'is_achieved' => rand(0, 10) < 4,
                    'achieved_date' => rand(0, 10) < 4 ? now()->subDays(rand(1, 30)) : null,
                ];

            case 'flexibility':
                $targetAngle = rand(90, 180);
                return [
                    'title' => 'Improve Flexibility',
                    'description' => 'Increase flexibility in major muscle groups.',
                    'target_value' => $targetAngle,
                    'current_value' => $targetAngle - rand(10, 30),
                    'unit' => 'degrees',
                    'is_achieved' => rand(0, 10) < 3,
                    'achieved_date' => rand(0, 10) < 3 ? now()->subDays(rand(1, 30)) : null,
                ];

            case 'muscle_gain':
                $currentWeight = $user->weight_kg ?? 70;
                $targetWeight = $currentWeight + rand(3, 10);
                return [
                    'title' => 'Muscle Gain Goal',
                    'description' => 'Build lean muscle mass through strength training.',
                    'target_value' => $targetWeight,
                    'current_value' => $currentWeight + rand(0, 2),
                    'unit' => 'kg',
                    'is_achieved' => rand(0, 10) < 2,
                    'achieved_date' => rand(0, 10) < 2 ? now()->subDays(rand(1, 30)) : null,
                ];

            case 'general_fitness':
                $targetScore = rand(70, 100);
                return [
                    'title' => 'General Fitness Improvement',
                    'description' => 'Improve overall fitness level and health.',
                    'target_value' => $targetScore,
                    'current_value' => $targetScore - rand(10, 20),
                    'unit' => 'score',
                    'is_achieved' => rand(0, 10) < 3,
                    'achieved_date' => rand(0, 10) < 3 ? now()->subDays(rand(1, 30)) : null,
                ];

            case 'sports_performance':
                $targetTime = rand(10, 30);
                return [
                    'title' => 'Sports Performance Goal',
                    'description' => 'Improve performance in specific sport.',
                    'target_value' => $targetTime,
                    'current_value' => $targetTime - rand(2, 8),
                    'unit' => 'seconds',
                    'is_achieved' => rand(0, 10) < 2,
                    'achieved_date' => rand(0, 10) < 2 ? now()->subDays(rand(1, 30)) : null,
                ];

            default:
                return [
                    'title' => 'General Fitness Goal',
                    'description' => 'Improve overall fitness and health.',
                    'target_value' => 100,
                    'current_value' => rand(60, 90),
                    'unit' => 'points',
                    'is_achieved' => rand(0, 10) < 3,
                    'achieved_date' => rand(0, 10) < 3 ? now()->subDays(rand(1, 30)) : null,
                ];
        }
    }
}
