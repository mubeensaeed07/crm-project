<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserInfo;
use App\Models\Department;
use App\Models\SalaryPayment;
use Illuminate\Support\Facades\Hash;

class FinanceModuleTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get or create departments
        $departments = Department::all();
        if ($departments->isEmpty()) {
            $departments = collect([
                Department::create(['name' => 'HR', 'code' => 'HR', 'description' => 'Human Resources', 'is_active' => true]),
                Department::create(['name' => 'IT', 'code' => 'IT', 'description' => 'Information Technology', 'is_active' => true]),
                Department::create(['name' => 'Finance', 'code' => 'FIN', 'description' => 'Finance Department', 'is_active' => true]),
            ]);
        }
        
        // Create test users with different roles
        $testUsers = [
            // Regular Users (role_id = 3)
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@test.com',
                'role_id' => 3,
                'salary' => 5000,
                'department' => $departments->first()
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@test.com',
                'role_id' => 3,
                'salary' => 6000,
                'department' => $departments->skip(1)->first()
            ],
            // Supervisors (role_id = 4)
            [
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike.johnson@test.com',
                'role_id' => 4,
                'salary' => 8000,
                'department' => $departments->last()
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Wilson',
                'email' => 'sarah.wilson@test.com',
                'role_id' => 4,
                'salary' => 9000,
                'department' => $departments->first()
            ]
        ];
        
        foreach ($testUsers as $userData) {
            // Create user
            $user = User::create([
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'role_id' => $userData['role_id'],
                'password' => Hash::make('password123'),
                'is_approved' => true,
                'created_at' => now()->subDays(rand(1, 30)) // Random creation date
            ]);
            
            // Create user info with salary and department
            $user->userInfo()->create([
                'salary' => $userData['salary'],
                'department_id' => $userData['department']->id,
                'hire_date' => $user->created_at,
                'phone' => '123-456-7890',
                'address' => '123 Test Street'
            ]);
            
            // Create salary payment records
            $this->createSalaryPayments($user, $userData['salary']);
        }
        
        $this->command->info('Finance module test data created successfully!');
    }
    
    private function createSalaryPayments($user, $salary)
    {
        // Create a paid salary payment
        SalaryPayment::create([
            'user_id' => $user->id,
            'amount' => $salary,
            'status' => 'paid',
            'paid_by' => 1, // Admin user
            'paid_at' => now()->subDays(rand(1, 15)),
            'next_payment_date' => $this->calculateNextPaymentDate($user->created_at),
            'notes' => 'Test salary payment'
        ]);
        
        // Create a pending salary payment
        SalaryPayment::create([
            'user_id' => $user->id,
            'amount' => $salary,
            'status' => 'pending',
            'paid_by' => null,
            'paid_at' => null,
            'next_payment_date' => null,
            'notes' => 'Next month salary - pending'
        ]);
    }
    
    private function calculateNextPaymentDate($userCreatedDate)
    {
        $dayOfMonth = $userCreatedDate->day;
        $nextPaymentDate = now()->copy()->day($dayOfMonth)->addMonth();
        
        if ($nextPaymentDate->isPast()) {
            $nextPaymentDate = $nextPaymentDate->addMonth();
        }
        
        return $nextPaymentDate;
    }
}