<?php

namespace Database\Seeders;

use App\Models\BodyMeasurement;
use App\Models\User;
use Illuminate\Database\Seeder;

class BodyMeasurementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BodyMeasurement::truncate();

        $users = User::all();
        $bodyMeasurements = [];

        foreach ($users as $user) {
            $baseWeight = $user->weight_kg ?? 70;
            $baseHeight = $user->height_cm ?? 170;
            
            // Create measurements for the last 6 months (weekly)
            for ($week = 0; $week < 24; $week++) {
                $measurementDate = now()->subWeeks($week);
                
                // Simulate gradual changes in measurements
                $weightVariation = rand(-2, 2) + ($week * 0.1); // Slight trend over time
                $weight = max(50, $baseWeight + $weightVariation + (rand(-5, 5) / 10));
                
                $bodyFat = rand(12, 25) + ($week * 0.05); // Slight trend
                $muscleMass = ($weight * 0.4) + rand(-2, 2);
                
                // Body measurements with slight variations
                $chest = ($baseHeight * 0.5) + rand(-5, 5);
                $waist = ($baseHeight * 0.45) + rand(-8, 8);
                $hips = ($baseHeight * 0.52) + rand(-5, 5);
                $thigh = ($baseHeight * 0.25) + rand(-3, 3);
                $arm = ($baseHeight * 0.15) + rand(-2, 2);
                $neck = ($baseHeight * 0.12) + rand(-1, 1);
                
                // Calculate BMI
                $heightInMeters = $baseHeight / 100;
                $bmi = round($weight / ($heightInMeters * $heightInMeters), 1);

                $bodyMeasurements[] = [
                    'user_id' => $user->id,
                    'measurement_date' => $measurementDate->toDateString(),
                    'weight_kg' => round($weight, 1),
                    'body_fat_percentage' => round($bodyFat, 1),
                    'muscle_mass_kg' => round($muscleMass, 1),
                    'chest_cm' => round($chest, 1),
                    'waist_cm' => round($waist, 1),
                    'hip_cm' => round($hips, 1),
                    'thigh_cm' => round($thigh, 1),
                    'arm_cm' => round($arm, 1),
                    'bmi' => $bmi,
                    'notes' => $this->getRandomNotes(),
                ];
            }
        }

        foreach ($bodyMeasurements as $measurement) {
            BodyMeasurement::create($measurement);
        }

        $this->command->info('Successfully seeded ' . count($bodyMeasurements) . ' body measurements!');
    }

    private function getRandomNotes(): ?string
    {
        $notes = [
            'Feeling stronger and more defined.',
            'Noticeable progress in muscle tone.',
            'Weight is stable, feeling good.',
            'Need to focus more on nutrition.',
            'Great progress this week!',
            'Feeling leaner and more energetic.',
            'Muscle definition is improving.',
            'Weight loss is on track.',
            'Feeling more confident in my body.',
            'Good consistency with measurements.',
            'Need to increase protein intake.',
            'Feeling healthy and strong.',
        ];

        return rand(0, 3) === 0 ? $notes[array_rand($notes)] : null;
    }
}
