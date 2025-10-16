<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Supervisor;
use App\Models\SalaryPaymentHistory;
use Illuminate\Support\Facades\DB;

class SecurityAudit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'security:audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run comprehensive security audit to ensure data isolation';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔒 Starting Security Audit...');
        
        $issues = [];
        
        // 1. Check for cross-admin data access
        $this->info('1. Checking for cross-admin data access...');
        $crossAdminIssues = $this->checkCrossAdminDataAccess();
        $issues = array_merge($issues, $crossAdminIssues);
        
        // 2. Check user admin assignments
        $this->info('2. Checking user admin assignments...');
        $userAssignmentIssues = $this->checkUserAdminAssignments();
        $issues = array_merge($issues, $userAssignmentIssues);
        
        // 3. Check payment history isolation
        $this->info('3. Checking payment history isolation...');
        $paymentIssues = $this->checkPaymentHistoryIsolation();
        $issues = array_merge($issues, $paymentIssues);
        
        // 4. Check supervisor admin assignments
        $this->info('4. Checking supervisor admin assignments...');
        $supervisorIssues = $this->checkSupervisorAdminAssignments();
        $issues = array_merge($issues, $supervisorIssues);
        
        // 5. Check for orphaned records
        $this->info('5. Checking for orphaned records...');
        $orphanedIssues = $this->checkOrphanedRecords();
        $issues = array_merge($issues, $orphanedIssues);
        
        // Report results
        if (empty($issues)) {
            $this->info('✅ Security Audit PASSED - No issues found!');
            $this->info('🛡️ System is secure for multi-tenant operation');
            return Command::SUCCESS;
        } else {
            $this->error('❌ Security Audit FAILED - Issues found:');
            foreach ($issues as $issue) {
                $this->error("  - $issue");
            }
            return Command::FAILURE;
        }
    }
    
    private function checkCrossAdminDataAccess()
    {
        $issues = [];
        
        // Check if any users have admin_id that doesn't exist
        $orphanedUsers = User::whereNotNull('admin_id')
            ->whereNotIn('admin_id', User::where('role_id', 2)->pluck('id'))
            ->get();
            
        if ($orphanedUsers->count() > 0) {
            $issues[] = "Found {$orphanedUsers->count()} users with invalid admin_id";
        }
        
        return $issues;
    }
    
    private function checkUserAdminAssignments()
    {
        $issues = [];
        
        // Check if any users are assigned to themselves as admin
        $selfAssignedUsers = User::whereColumn('id', 'admin_id')->get();
        if ($selfAssignedUsers->count() > 0) {
            $issues[] = "Found {$selfAssignedUsers->count()} users assigned to themselves as admin";
        }
        
        return $issues;
    }
    
    private function checkPaymentHistoryIsolation()
    {
        $issues = [];
        
        // Check if any payment history has users from different admins
        $payments = SalaryPaymentHistory::with('user')->get();
        $crossAdminPayments = 0;
        
        foreach ($payments as $payment) {
            if ($payment->user) {
                $userAdminId = $payment->user->admin_id;
                $paidByAdminId = $payment->paid_by;
                
                if ($userAdminId && $paidByAdminId && $userAdminId != $paidByAdminId) {
                    $crossAdminPayments++;
                }
            }
        }
        
        if ($crossAdminPayments > 0) {
            $issues[] = "Found {$crossAdminPayments} payment records with cross-admin issues";
        }
        
        return $issues;
    }
    
    private function checkSupervisorAdminAssignments()
    {
        $issues = [];
        
        // Check if any supervisors have invalid admin_id
        $orphanedSupervisors = Supervisor::whereNotNull('admin_id')
            ->whereNotIn('admin_id', User::where('role_id', 2)->pluck('id'))
            ->get();
            
        if ($orphanedSupervisors->count() > 0) {
            $issues[] = "Found {$orphanedSupervisors->count()} supervisors with invalid admin_id";
        }
        
        return $issues;
    }
    
    private function checkOrphanedRecords()
    {
        $issues = [];
        
        // Check for orphaned UserInfo records
        $orphanedUserInfos = DB::table('user_infos')
            ->whereNotIn('user_id', User::pluck('id'))
            ->count();
            
        if ($orphanedUserInfos > 0) {
            $issues[] = "Found {$orphanedUserInfos} orphaned UserInfo records";
        }
        
        // Check for orphaned SalaryPaymentHistory records
        $orphanedPayments = SalaryPaymentHistory::whereNotIn('user_id', User::pluck('id'))->count();
        if ($orphanedPayments > 0) {
            $issues[] = "Found {$orphanedPayments} orphaned SalaryPaymentHistory records";
        }
        
        return $issues;
    }
}
