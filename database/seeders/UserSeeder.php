<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::truncate();

        $users = [
            [
                'username' => 'john_doe',
                'email' => 'john.doe@example.com',
                'password_hash' => Hash::make('password123'),
                'first_name' => 'John',
                'last_name' => 'Doe',
                'date_of_birth' => '1990-05-15',
                'gender' => 'male',
                'height_cm' => 180,
                'weight_kg' => 75.5,
                'activity_level' => 'moderately_active',
                'fitness_goal' => 'muscle_gain',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'username' => 'jane_smith',
                'email' => 'jane.smith@example.com',
                'password_hash' => Hash::make('password123'),
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'date_of_birth' => '1988-08-22',
                'gender' => 'female',
                'height_cm' => 165,
                'weight_kg' => 60.0,
                'activity_level' => 'very_active',
                'fitness_goal' => 'weight_loss',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'username' => 'mike_wilson',
                'email' => 'mike.wilson@example.com',
                'password_hash' => Hash::make('password123'),
                'first_name' => 'Mike',
                'last_name' => 'Wilson',
                'date_of_birth' => '1992-12-10',
                'gender' => 'male',
                'height_cm' => 175,
                'weight_kg' => 80.0,
                'activity_level' => 'extremely_active',
                'fitness_goal' => 'strength',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'username' => 'sarah_jones',
                'email' => 'sarah.jones@example.com',
                'password_hash' => Hash::make('password123'),
                'first_name' => 'Sarah',
                'last_name' => 'Jones',
                'date_of_birth' => '1995-03-18',
                'gender' => 'female',
                'height_cm' => 170,
                'weight_kg' => 65.0,
                'activity_level' => 'lightly_active',
                'fitness_goal' => 'general_fitness',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'username' => 'david_brown',
                'email' => 'david.brown@example.com',
                'password_hash' => Hash::make('password123'),
                'first_name' => 'David',
                'last_name' => 'Brown',
                'date_of_birth' => '1985-07-05',
                'gender' => 'male',
                'height_cm' => 185,
                'weight_kg' => 90.0,
                'activity_level' => 'sedentary',
                'fitness_goal' => 'endurance',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'username' => 'lisa_davis',
                'email' => 'lisa.davis@example.com',
                'password_hash' => Hash::make('password123'),
                'first_name' => 'Lisa',
                'last_name' => 'Davis',
                'date_of_birth' => '1991-11-30',
                'gender' => 'female',
                'height_cm' => 160,
                'weight_kg' => 55.0,
                'activity_level' => 'moderately_active',
                'fitness_goal' => 'general_fitness',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'username' => 'alex_taylor',
                'email' => 'alex.taylor@example.com',
                'password_hash' => Hash::make('password123'),
                'first_name' => 'Alex',
                'last_name' => 'Taylor',
                'date_of_birth' => '1993-09-14',
                'gender' => 'other',
                'height_cm' => 172,
                'weight_kg' => 70.0,
                'activity_level' => 'very_active',
                'fitness_goal' => 'maintenance',
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'username' => 'emma_wilson',
                'email' => 'emma.wilson@example.com',
                'password_hash' => Hash::make('password123'),
                'first_name' => 'Emma',
                'last_name' => 'Wilson',
                'date_of_birth' => '1987-04-25',
                'gender' => 'female',
                'height_cm' => 168,
                'weight_kg' => 62.0,
                'activity_level' => 'moderately_active',
                'fitness_goal' => 'muscle_gain',
                'is_active' => false,
                'email_verified_at' => null,
            ],
        ];

        foreach ($users as $user) {
            User::create($user);
        }

        $this->command->info('Successfully seeded ' . count($users) . ' users!');
    }
}
