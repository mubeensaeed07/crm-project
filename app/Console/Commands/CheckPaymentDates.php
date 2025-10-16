<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SalaryPayment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\PaymentDueNotification;

class CheckPaymentDates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'finance:check-payment-dates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for payment due dates and update statuses automatically';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting payment date check...');
        
        $today = Carbon::today();
        $updatedCount = 0;
        $notifiedCount = 0;
        
        // Find all paid salaries where next_payment_date is today or overdue
        $duePayments = SalaryPayment::where('status', 'paid')
            ->where('next_payment_date', '<=', $today)
            ->with('user')
            ->get();
            
        $this->info("Found {$duePayments->count()} payments due today or overdue");
        
        foreach ($duePayments as $payment) {
            // Update status to pending
            $payment->update([
                'status' => 'pending',
                'next_payment_date' => null // Clear next payment date since it's now pending
            ]);
            
            // Create payment history record for tracking
            \App\Models\SalaryPaymentHistory::create([
                'user_id' => $payment->user_id,
                'amount' => $payment->amount,
                'payment_month' => now()->format('Y-m'),
                'payment_year' => now()->format('Y'),
                'status' => 'pending',
                'paid_by' => null, // System generated
                'paid_by_type' => 'system',
                'paid_by_name' => 'System (Auto)',
                'paid_at' => null,
                'due_date' => $payment->next_payment_date,
                'notes' => 'Payment due date reached - automatically set to pending'
            ]);
            
            $updatedCount++;
            
            // Log the update
            Log::info("Payment status updated to pending", [
                'user_id' => $payment->user_id,
                'user_name' => $payment->user->full_name ?? 'Unknown',
                'amount' => $payment->amount,
                'due_date' => $payment->next_payment_date
            ]);
            
            $this->line("Updated payment for {$payment->user->full_name} to pending");
        }
        
        // Find all users with pending payments (overdue)
        $overdueUsers = User::whereHas('salaryPayments', function($query) use ($today) {
            $query->where('status', 'pending')
                  ->where('next_payment_date', '<', $today);
        })->with(['salaryPayments' => function($query) use ($today) {
            $query->where('status', 'pending')
                  ->where('next_payment_date', '<', $today);
        }])->get();
        
        // Send notifications for overdue payments
        foreach ($overdueUsers as $user) {
            try {
                // Get admin users to notify
                $admins = User::whereIn('role_id', [1, 2])->get(); // SuperAdmin and Admin
                
                foreach ($admins as $admin) {
                    Mail::to($admin->email)->send(new PaymentDueNotification($user, $admin));
                    $notifiedCount++;
                }
                
                $this->line("Sent overdue notification for {$user->full_name}");
                
            } catch (\Exception $e) {
                Log::error("Failed to send payment due notification", [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        $this->info("Payment check completed!");
        $this->info("Updated {$updatedCount} payments to pending");
        $this->info("Sent {$notifiedCount} notifications");
        
        return Command::SUCCESS;
    }
}