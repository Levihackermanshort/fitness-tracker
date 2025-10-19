<?php

namespace Database\Seeders;

use App\Models\WorkoutPlan;
use App\Models\User;
use Illuminate\Database\Seeder;

class WorkoutPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WorkoutPlan::truncate();

        $users = User::all();
        $planTypes = ['strength', 'cardio', 'flexibility', 'mixed', 'sports', 'rehabilitation', 'other'];
        
        $workoutPlans = [];

        // Create template plans (not associated with specific users)
        $templatePlans = [
            [
                'name' => 'Beginner Strength Program',
                'description' => 'A 12-week program designed for beginners to build foundational strength.',
                'plan_type' => 'strength',
                'difficulty_level' => 'beginner',
                'duration_weeks' => 12,
                'sessions_per_week' => 3,
                'estimated_duration_per_session' => 45,
                'is_template' => true,
            ],
            [
                'name' => 'Cardio Blast Challenge',
                'description' => 'High-intensity cardio program for fat loss and endurance.',
                'plan_type' => 'cardio',
                'difficulty_level' => 'intermediate',
                'duration_weeks' => 8,
                'sessions_per_week' => 5,
                'estimated_duration_per_session' => 30,
                'is_template' => true,
            ],
            [
                'name' => 'Bodyweight Mastery',
                'description' => 'Complete bodyweight training program requiring no equipment.',
                'plan_type' => 'mixed',
                'difficulty_level' => 'intermediate',
                'duration_weeks' => 10,
                'sessions_per_week' => 4,
                'estimated_duration_per_session' => 40,
                'is_template' => true,
            ],
            [
                'name' => 'Hybrid Athlete',
                'description' => 'Combines strength and cardio for overall fitness.',
                'plan_type' => 'mixed',
                'difficulty_level' => 'advanced',
                'duration_weeks' => 16,
                'sessions_per_week' => 6,
                'estimated_duration_per_session' => 60,
                'is_template' => true,
            ],
            [
                'name' => 'Basketball Conditioning',
                'description' => 'Sport-specific training for basketball players.',
                'plan_type' => 'sports',
                'difficulty_level' => 'intermediate',
                'duration_weeks' => 8,
                'sessions_per_week' => 4,
                'estimated_duration_per_session' => 50,
                'is_template' => true,
            ],
            [
                'name' => 'Rehabilitation Program',
                'description' => 'Gentle program for injury recovery and prevention.',
                'plan_type' => 'rehabilitation',
                'difficulty_level' => 'beginner',
                'duration_weeks' => 12,
                'sessions_per_week' => 3,
                'estimated_duration_per_session' => 30,
                'is_template' => true,
            ],
        ];

        foreach ($templatePlans as $template) {
            $workoutPlans[] = array_merge($template, [
                'user_id' => $users->first()->id, // Assign templates to first user
                'is_active' => true,
            ]);
        }

        // Create user-specific plans
        foreach ($users as $user) {
            // 1-3 plans per user
            $planCount = rand(1, 3);
            
            for ($i = 0; $i < $planCount; $i++) {
                $planType = $planTypes[array_rand($planTypes)];
                $planName = $this->generatePlanName($planType, $user);
                
                $workoutPlans[] = [
                    'user_id' => $user->id,
                    'name' => $planName,
                    'description' => $this->generatePlanDescription($planType),
                    'plan_type' => $planType,
                    'difficulty_level' => ['beginner', 'intermediate', 'advanced'][rand(0, 2)],
                    'duration_weeks' => rand(4, 16),
                    'sessions_per_week' => rand(2, 6),
                    'estimated_duration_per_session' => rand(30, 90),
                    'is_active' => rand(0, 10) < 8, // 80% active
                    'is_template' => false,
                    'notes' => $this->getRandomNotes(),
                ];
            }
        }

        foreach ($workoutPlans as $plan) {
            WorkoutPlan::create($plan);
        }

        $this->command->info('Successfully seeded ' . count($workoutPlans) . ' workout plans!');
    }

    private function generatePlanName(string $planType, $user): string
    {
        $names = [
            'strength' => [
                "{$user->first_name}'s Strength Journey",
                'Power Building Program',
                'Strength Foundation',
                'Muscle Building Plan',
            ],
            'cardio' => [
                "{$user->first_name}'s Cardio Challenge",
                'Endurance Builder',
                'Fat Loss Cardio',
                'Heart Health Program',
            ],
            'mixed' => [
                "{$user->first_name}'s Complete Fitness",
                'Balanced Training',
                'All-Around Athlete',
                'Total Body Program',
            ],
            'flexibility' => [
                "{$user->first_name}'s Flexibility Journey",
                'Yoga & Stretching',
                'Mobility Mastery',
                'Flexibility Focus',
            ],
            'sports' => [
                "{$user->first_name}'s Sports Training",
                'Athletic Performance',
                'Sport-Specific Plan',
                'Competition Prep',
            ],
            'other' => [
                "{$user->first_name}'s Custom Plan",
                'Personalized Program',
                'Specialized Training',
                'Unique Approach',
            ],
            'rehabilitation' => [
                "{$user->first_name}'s Recovery Plan",
                'Gentle Rehabilitation',
                'Injury Prevention',
                'Safe Return to Fitness',
            ],
        ];

        $typeNames = $names[$planType] ?? $names['mixed'];
        return $typeNames[array_rand($typeNames)];
    }

    private function generatePlanDescription(string $planType): string
    {
        $descriptions = [
            'strength' => 'Focus on building muscle mass and increasing strength through progressive overload.',
            'cardio' => 'High-intensity cardiovascular training to improve endurance and burn calories.',
            'flexibility' => 'Program focused on improving flexibility, mobility, and range of motion.',
            'mixed' => 'Balanced approach combining strength training and cardiovascular exercise.',
            'sports' => 'Training program designed to improve performance in specific sports.',
            'rehabilitation' => 'Gentle exercise program focused on recovery and injury prevention.',
            'other' => 'Customized fitness program tailored to specific needs and goals.',
        ];

        return $descriptions[$planType] ?? 'Comprehensive fitness program designed for overall health and wellness.';
    }

    private function getRandomNotes(): ?string
    {
        $notes = [
            'Great progress so far!',
            'Feeling stronger each week.',
            'Need to adjust intensity.',
            'Perfect for my schedule.',
            'Challenging but achievable.',
            'Love the variety.',
            'Seeing real results.',
            'Need more rest days.',
            'Excellent program design.',
            'Highly recommended.',
        ];

        return rand(0, 3) === 0 ? $notes[array_rand($notes)] : null;
    }
}
