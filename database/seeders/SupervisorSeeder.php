<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Supervisor;
use App\Models\User;
use App\Models\Module;
use Illuminate\Support\Facades\Hash;

class SupervisorSeeder extends Seeder
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

        // Create supervisor
        $supervisor = Supervisor::create([
            'first_name' => 'John',
            'last_name' => 'Wick',
            'email' => 'john@gmail.com',
            'password' => Hash::make('password123'),
            'status' => 'active',
            'admin_id' => $superAdmin->id,
            'superadmin_id' => $superAdmin->id,
        ]);

        // Get HRM module
        $hrmModule = Module::where('name', 'HRM')->first();
        
        if ($hrmModule) {
            // Assign HRM module to supervisor with all permissions
            $supervisor->modules()->attach($hrmModule->id, [
                'can_create_users' => true,
                'can_edit_users' => true,
                'can_delete_users' => true,
                'can_reset_passwords' => true,
                'can_assign_modules' => true,
                'can_view_reports' => true,
            ]);
        }

        $this->command->info('Supervisor created: john@gmail.com / password123');
    }
}