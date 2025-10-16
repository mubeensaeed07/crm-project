<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the SuperAdmin user
        $superAdmin = User::where('role_id', 1)->first();
        
        if (!$superAdmin) {
            $this->command->error('No SuperAdmin found. Please create a SuperAdmin first.');
            return;
        }

        // Create user for Google OAuth
        $user = User::create([
            'first_name' => 'GOLDEN',
            'last_name' => 'Miharu',
            'email' => 'goldenmiharu@gmail.com',
            'password' => Hash::make('password123'),
            'role_id' => 3, // Regular user
            'is_approved' => true,
            'admin_id' => $superAdmin->id,
            'superadmin_id' => $superAdmin->id,
            'created_by_type' => 'admin',
            'created_by_id' => $superAdmin->id,
        ]);

        // Create user info
        UserInfo::create([
            'user_id' => $user->id,
            'phone' => '+1234567890',
            'job_title' => 'Developer',
            'company' => 'Tech Corp',
            'superadmin_id' => $superAdmin->id,
        ]);

        $this->command->info('User created: goldenmiharu@gmail.com / password123');
    }
}