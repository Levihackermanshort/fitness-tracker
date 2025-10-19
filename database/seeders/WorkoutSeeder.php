<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Workout;
use Carbon\Carbon;

class WorkoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing workouts
        Workout::truncate();

        $workouts = [
            // Last week's workouts
            [
                'date' => Carbon::now()->subDays(6),
                'exercise' => 'Morning Jogging',
                'duration' => 45,
                'calories' => 420,
                'notes' => 'Beautiful sunrise jog around the park. Felt energized all day!',
            ],
            [
                'date' => Carbon::now()->subDays(5),
                'exercise' => 'Weight Training',
                'duration' => 90,
                'calories' => 580,
                'notes' => 'Upper body strength training. Increased weights on bench press.',
            ],
            [
                'date' => Carbon::now()->subDays(4),
                'exercise' => 'Yoga Flow',
                'duration' => 60,
                'calories' => 180,
                'notes' => 'Restorative yoga session. Great for flexibility and relaxation.',
            ],
            [
                'date' => Carbon::now()->subDays(3),
                'exercise' => 'Swimming',
                'duration' => 30,
                'calories' => 280,
                'notes' => 'Pool swim - freestyle and backstroke mixed. Water was perfect.',
            ],
            [
                'date' => Carbon::now()->subDays(2),
                'exercise' => 'Cycling',
                'duration' => 120,
                'calories' => 850,
                'notes' => 'Long bike ride through countryside. Amazing scenery and fresh air.',
            ],
            [
                'date' => Carbon::now()->subDay(),
                'exercise' => 'HIIT Training',
                'duration' => 25,
                'calories' => 320,
                'notes' => 'High intensity interval training. Tough but rewarding workout!',
            ],
            [
                'date' => Carbon::now(),
                'exercise' => 'Evening Walk',
                'duration' => 35,
                'calories' => 150,
                'notes' => 'Peaceful evening walk with family. Perfect way to wind down.',
            ],
            // Previous weeks' workouts
            [
                'date' => Carbon::now()->subDays(8),
                'exercise' => 'Basketball',
                'duration' => 60,
                'calories' => 480,
                'notes' => 'Pickup game at local court. Amazing teamwork and competition.',
            ],
            [
                'date' => Carbon::now()->subDays(10),
                'exercise' => 'Rock Climbing',
                'duration' => 150,
                'calories' => 650,
                'notes' => 'Indoor climbing session. Conquered 4 new routes today!',
            ],
            [
                'date' => Carbon::now()->subDays(12),
                'exercise' => 'Pilates',
                'duration' => 55,
                'calories' => 200,
                'notes' => 'Core-focused Pilates class. Challenging but satisfying.',
            ],
            [
                'date' => Carbon::now()->subDays(14),
                'exercise' => 'Running',
                'duration' => 75,
                'calories' => 750,
                'notes' => 'Long distance run. Managed 10K in personal best time!',
            ],
            [
                'date' => Carbon::now()->subDays(16),
                'exercise' => 'Crossfit',
                'duration' => 50,
                'calories' => 400,
                'notes' => 'Crossfit WOD - burpees, kettlebell swings, and box jumps.',
            ],
            [
                'date' => Carbon::now()->subDays(18),
                'exercise' => 'Tennis',
                'duration' => 90,
                'calories' => 520,
                'notes' => 'Doubles match with friends. Great rallies and strategy.',
            ],
            [
                'date' => Carbon::now()->subDays(20),
                'exercise' => 'Hiking',
                'duration' => 180,
                'calories' => 1100,
                'notes' => 'Mountain trail hike. Spectacular views from the summit!',
            ],
            [
                'date' => Carbon::now()->subDays(22),
                'exercise' => 'Martial Arts',
                'duration' => 80,
                'calories' => 450,
                'notes' => 'Brazilian Jiu-Jitsu class. Learned new submission technique.',
            ],
            [
                'date' => Carbon::now()->subDays(24),
                'exercise' => 'Rowing',
                'duration' => 40,
                'calories' => 300,
                'notes' => 'Indoor rowing machine. Focused on technique and power.',
            ],
            [
                'date' => Carbon::now()->subDays(26),
                'exercise' => 'Dancing',
                'duration' => 65,
                'calories' => 350,
                'notes' => 'Salsa dancing class. Great cardio and lots of fun!',
            ],
            [
                'date' => Carbon::now()->subDays(28),
                'exercise' => 'Boxing',
                'duration' => 45,
                'calories' => 380,
                'notes' => 'Boxing training session. Heavy bag work and footwork drills.',
            ],
            [
                'date' => Carbon::now()->subDays(30),
                'exercise' => 'Cross Training',
                'duration' => 70,
                'calories' => 500,
                'notes' => 'Mixed cardio and strength training. Circuit style workout.',
            ],
            [
                'date' => Carbon::now()->subDays(32),
                'exercise' => 'Surfing',
                'duration' => 120,
                'calories' => 700,
                'notes' => 'Morning surf session. Perfect waves and sunny weather.',
            ],
            [
                'date' => Carbon::now()->subDays(34),
                'exercise' => 'Gymnastics',
                'duration' => 100,
                'calories' => 420,
                'notes' => 'Adult gymnastics class. Working on handstands and flexibility.',
            ],
            [
                'date' => Carbon::now()->subDays(36),
                'exercise' => 'Functional Training',
                'duration' => 55,
                'calories' => 400,
                'notes' => 'Full body functional movement patterns. TRX and kettlebells.',
            ],
            [
                'date' => Carbon::now()->subDays(38),
                'exercise' => 'Paddleboarding',
                'duration' => 105,
                'calories' => 480,
                'notes' => 'Stand-up paddleboarding on the lake. Peaceful yet challenging.',
            ],
        ];

        // Insert workouts
        foreach ($workouts as $workout) {
            Workout::create($workout);
        }

        $this->command->info('Successfully seeded ' . count($workouts) . ' workouts!');
    }
}
