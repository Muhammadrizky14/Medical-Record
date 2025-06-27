<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create doctor user
        $doctorUser = User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@example.com',
            'password' => Hash::make('password'),
            'role' => 'doctor',
            'specialization' => 'General Medicine',
            'phone' => '0812345678',
            'address' => '123 Medical Street',
        ]);

        // Create doctor record
        Doctor::create([
            'name' => 'Dr. John Smith',
            'specialization' => 'General Medicine',
            'phone' => '0812345678',
            'email' => 'doctor@example.com',
            'address' => '123 Medical Street',
            'user_id' => $doctorUser->id,
        ]);

        // Create sample patients
        Patient::create([
            'name' => 'Jane Doe',
            'birth_date' => '1990-01-15',
            'gender' => 'female',
            'phone' => '0811111111',
            'address' => '456 Patient Avenue',
            'medical_history' => 'No significant medical history',
        ]);

        Patient::create([
            'name' => 'Bob Johnson',
            'birth_date' => '1985-05-20',
            'gender' => 'male',
            'phone' => '0822222222',
            'address' => '789 Health Boulevard',
            'medical_history' => 'Hypertension, Diabetes',
        ]);
    }
}
