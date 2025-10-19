<?php

namespace Database\Seeders;

use App\Models\WorkoutSession;
use App\Models\User;
use Illuminate\Database\Seeder;

class WorkoutSessionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkoutSession::truncate();

        $users = User::all();
        $workoutTypes = ['strength', 'cardio', 'flexibility', 'mixed', 'sports', 'other'];
        $workoutNames = [
            'Morning Strength Session',
            'Cardio Blast',
            'Yoga Flow',
            'HIIT Workout',
            'Swimming Session',
            'Basketball Game',
            'Evening Run',
            'Weight Training',
            'Pilates Class',
            'Cycling Workout',
            'Boxing Session',
            'CrossFit WOD',
            'Tennis Match',
            'Hiking Adventure',
            'Dance Fitness',
        ];

        $workoutSessions = [];

        foreach ($users as $user) {
            // Create 10-15 workout sessions per user over the last 3 months
            $sessionCount = rand(10, 15);
            
            for ($i = 0; $i < $sessionCount; $i++) {
                $date = now()->subDays(rand(1, 90));
                $workoutType = $workoutTypes[array_rand($workoutTypes)];
                $workoutName = $workoutNames[array_rand($workoutNames)];
                
                $startTime = $date->copy()->setHour(rand(6, 20))->setMinute(rand(0, 59));
                $duration = rand(30, 120); // 30-120 minutes
                $endTime = $startTime->copy()->addMinutes($duration);
                
                $calories = match($workoutType) {
                    'strength' => rand(200, 500),
                    'cardio' => rand(300, 800),
                    'flexibility' => rand(100, 200),
                    'mixed' => rand(250, 600),
                    'sports' => rand(400, 700),
                    'outdoor' => rand(300, 600),
                    default => rand(200, 500)
                };

                $moodBefore = rand(1, 5);
                $moodAfter = min(5, $moodBefore + rand(0, 2)); // Mood usually improves

                $workoutSessions[] = [
                    'user_id' => $user->id,
                    'name' => $workoutName,
                    'date' => $date->toDateString(),
                    'description' => $this->getRandomNotes($workoutType),
                    'workout_type' => $workoutType,
                    'total_duration' => $duration,
                    'total_calories' => $calories,
                    'perceived_exertion' => rand(3, 9),
                    'mood_before' => $moodBefore,
                    'mood_after' => $moodAfter,
                    'notes' => $this->getRandomNotes($workoutType),
                ];
            }
        }

        foreach ($workoutSessions as $session) {
            WorkoutSession::create($session);
        }

        $this->command->info('Successfully seeded ' . count($workoutSessions) . ' workout sessions!');
    }

    private function getRandomNotes(string $workoutType): ?string
    {
        $notes = [
            'strength' => [
                'Great session! Felt strong today.',
                'Increased weight on bench press.',
                'Need to work on form for squats.',
                'Excellent pump today.',
                'Felt tired but pushed through.',
            ],
            'cardio' => [
                'Good pace maintained throughout.',
                'Felt winded but kept going.',
                'New personal best!',
                'Enjoyed the music playlist.',
                'Need to work on endurance.',
            ],
            'flexibility' => [
                'Felt very relaxed after.',
                'Improved flexibility in hamstrings.',
                'Need to stretch more regularly.',
                'Great stress relief.',
                'Felt more limber today.',
            ],
            'mixed' => [
                'Good variety of exercises.',
                'Challenging but fun workout.',
                'Felt energized throughout.',
                'Great combination of strength and cardio.',
                'Enjoyed the variety.',
            ],
            'sports' => [
                'Great game with friends.',
                'Competitive match today.',
                'Improved my technique.',
                'Fun team activity.',
                'Good workout disguised as fun.',
            ],
            'outdoor' => [
                'Beautiful weather for exercise.',
                'Enjoyed the fresh air.',
                'Great scenery during workout.',
                'Felt connected to nature.',
                'Perfect temperature for outdoor activity.',
            ],
        ];

        $typeNotes = $notes[$workoutType] ?? $notes['mixed'];
        return rand(0, 3) === 0 ? $typeNotes[array_rand($typeNotes)] : null;
    }

    private function getRandomWeather(): ?string
    {
        $weather = ['sunny', 'cloudy', 'rainy', 'windy', 'hot', 'cold', 'perfect'];
        return rand(0, 2) === 0 ? $weather[array_rand($weather)] : null;
    }

    private function getRandomLocation(string $workoutType): ?string
    {
        $locations = [
            'strength' => ['Gym', 'Home Gym', 'CrossFit Box', 'Fitness Center'],
            'cardio' => ['Treadmill', 'Outdoor Track', 'Park', 'Beach'],
            'flexibility' => ['Yoga Studio', 'Home', 'Park', 'Community Center'],
            'mixed' => ['Gym', 'Home', 'Outdoor', 'Fitness Studio'],
            'sports' => ['Sports Complex', 'Court', 'Field', 'Arena'],
            'outdoor' => ['Park', 'Trail', 'Beach', 'Mountain'],
        ];

        $typeLocations = $locations[$workoutType] ?? $locations['mixed'];
        return $typeLocations[array_rand($typeLocations)];
    }
}
