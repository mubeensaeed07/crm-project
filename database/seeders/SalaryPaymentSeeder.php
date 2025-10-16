<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SalaryPayment;
use App\Models\User;

class SalaryPaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get only users and supervisors (exclude admin and superadmin)
        $users = User::whereNotIn('role_id', [1, 2])->get(); // Exclude SuperAdmin (1) and Admin (2)
        
        if ($users->count() > 0) {
            foreach ($users as $user) {
                // Create sample salary payments for each user
                $salaryAmount = $user->userInfo->salary ?? 5000; // Use user's salary or default 5000
                
                // Calculate next payment date based on user creation date
                $userCreatedDate = $user->created_at;
                $dayOfMonth = $userCreatedDate->day; // Get the day of month when user was created
                
                // Set next payment date to the same day next month
                $nextPaymentDate = now()->copy()->day($dayOfMonth)->addMonth();
                
                // If the calculated date is in the past, set it to next month
                if ($nextPaymentDate->isPast()) {
                    $nextPaymentDate = $nextPaymentDate->addMonth();
                }
                
                // Create a paid salary payment
                SalaryPayment::create([
                    'user_id' => $user->id,
                    'amount' => $salaryAmount,
                    'status' => 'paid',
                    'paid_by' => 1, // Admin user
                    'paid_at' => now()->subDays(rand(1, 30)),
                    'next_payment_date' => $nextPaymentDate,
                    'notes' => 'Monthly salary payment'
                ]);
                
                // Create a pending salary payment for next month
                SalaryPayment::create([
                    'user_id' => $user->id,
                    'amount' => $salaryAmount,
                    'status' => 'pending',
                    'paid_by' => null,
                    'paid_at' => null,
                    'next_payment_date' => null,
                    'notes' => 'Next month salary - pending'
                ]);
            }
        }
    }
}