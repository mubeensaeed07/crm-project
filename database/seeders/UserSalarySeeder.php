<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Department;

class UserSalarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create a default department
        $department = Department::first();
        if (!$department) {
            $department = Department::create([
                'name' => 'General',
                'description' => 'General Department',
                'code' => 'GEN',
                'is_active' => true
            ]);
        }
        
        // Get only users and supervisors (exclude admin and superadmin)
        $users = User::whereNotIn('role_id', [1, 2])->get(); // Exclude SuperAdmin (1) and Admin (2)
        $salaries = [5000, 6000, 7000, 8000, 9000, 10000]; // Sample salary amounts
        
        foreach ($users as $index => $user) {
            // Check if user already has userInfo
            $userInfo = UserInfo::where('user_id', $user->id)->first();
            
            if (!$userInfo) {
                // Create userInfo with salary
                UserInfo::create([
                    'user_id' => $user->id,
                    'salary' => $salaries[$index % count($salaries)], // Cycle through salary amounts
                    'department_id' => $department->id,
                    'position' => 'Employee',
                    'hire_date' => now()->subMonths(rand(1, 12)),
                    'phone' => '123-456-7890',
                    'address' => '123 Main St'
                ]);
            } else {
                // Update existing userInfo with salary if not set
                if (!$userInfo->salary) {
                    $userInfo->update([
                        'salary' => $salaries[$index % count($salaries)],
                        'department_id' => $department->id
                    ]);
                }
            }
        }
    }
}