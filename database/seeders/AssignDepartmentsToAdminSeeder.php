<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\User;

class AssignDepartmentsToAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the first admin (role_id = 2)
        $admin = User::where('role_id', 2)->where('is_approved', true)->first();
        
        if ($admin) {
            // Assign all existing departments to this admin
            Department::whereNull('admin_id')->update(['admin_id' => $admin->id]);
            
            $this->command->info("Assigned all departments to admin: {$admin->first_name} {$admin->last_name}");
        } else {
            $this->command->warn('No approved admin found. Departments remain unassigned.');
        }
    }
}