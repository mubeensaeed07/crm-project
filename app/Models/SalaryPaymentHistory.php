<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SalaryPaymentHistory extends Model
{
    use HasFactory;

    protected $table = 'salary_payment_history';

    protected $fillable = [
        'user_id',
        'amount',
        'payment_month',
        'payment_year',
        'status',
        'paid_by',
        'paid_by_type',
        'paid_by_name',
        'paid_at',
        'due_date',
        'notes'
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'due_date' => 'datetime',
        'amount' => 'decimal:2'
    ];

    /**
     * Get the user who received this payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who marked this payment as paid
     */
    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by');
    }

    /**
     * Get payment history for a specific month
     */
    public static function getMonthlyPayments($year, $month)
    {
        return self::where('payment_year', $year)
                   ->where('payment_month', $month)
                   ->with(['user', 'paidBy'])
                   ->orderBy('paid_at', 'desc')
                   ->get();
    }

    /**
     * Get payment history for a specific user
     */
    public static function getUserPaymentHistory($userId, $limit = null)
    {
        $query = self::where('user_id', $userId)
                    ->with(['user', 'paidBy'])
                    ->orderBy('payment_month', 'desc')
                    ->orderBy('paid_at', 'desc');
        
        if ($limit) {
            $query->limit($limit);
        }
        
        return $query->get();
    }

    /**
     * Get monthly payment statistics
     */
    public static function getMonthlyStats($year, $month)
    {
        $payments = self::where('payment_year', $year)
                       ->where('payment_month', $month)
                       ->get();

        return [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'paid_count' => $payments->where('status', 'paid')->count(),
            'pending_count' => $payments->where('status', 'pending')->count(),
            'overdue_count' => $payments->where('status', 'overdue')->count(),
            'paid_amount' => $payments->where('status', 'paid')->sum('amount'),
            'pending_amount' => $payments->where('status', 'pending')->sum('amount'),
            'overdue_amount' => $payments->where('status', 'overdue')->sum('amount'),
        ];
    }

    /**
     * Get yearly payment statistics
     */
    public static function getYearlyStats($year)
    {
        $payments = self::where('payment_year', $year)->get();

        return [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('amount'),
            'paid_count' => $payments->where('status', 'paid')->count(),
            'pending_count' => $payments->where('status', 'pending')->count(),
            'overdue_count' => $payments->where('status', 'overdue')->count(),
            'paid_amount' => $payments->where('status', 'paid')->sum('amount'),
            'pending_amount' => $payments->where('status', 'pending')->sum('amount'),
            'overdue_amount' => $payments->where('status', 'overdue')->sum('amount'),
            'monthly_breakdown' => $payments->groupBy('payment_month')->map(function ($monthPayments) {
                return [
                    'total_amount' => $monthPayments->sum('amount'),
                    'paid_amount' => $monthPayments->where('status', 'paid')->sum('amount'),
                    'pending_amount' => $monthPayments->where('status', 'pending')->sum('amount'),
                    'overdue_amount' => $monthPayments->where('status', 'overdue')->sum('amount'),
                ];
            })
        ];
    }

    /**
     * Create a new payment history record
     */
    public static function createPaymentRecord($userId, $amount, $status = 'pending', $paidBy = null, $notes = null)
    {
        $now = Carbon::now();
        $currentUser = auth()->user();
        
        return self::create([
            'user_id' => $userId,
            'amount' => $amount,
            'payment_month' => $now->format('Y-m'),
            'payment_year' => $now->format('Y'),
            'status' => $status,
            'paid_by' => $paidBy ?: $currentUser->id,
            'paid_by_type' => $currentUser->isAdmin() ? 'admin' : ($currentUser->isSupervisor() ? 'supervisor' : 'user'),
            'paid_by_name' => $currentUser->full_name,
            'paid_at' => $status === 'paid' ? $now : null,
            'due_date' => $now,
            'notes' => $notes
        ]);
    }
}