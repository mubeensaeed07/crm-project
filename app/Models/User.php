<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'is_approved',
        'admin_id',
        'superadmin_id',
        'phone'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_approved' => 'boolean'
        ];
    }

    /**
     * Get the user's profile information.
     */
    public function userInfo()
    {
        return $this->hasOne(UserInfo::class);
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute()
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }
        return 'User';
    }

    /**
     * Get the role that owns the user.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Get the modules assigned to the user.
     */
    public function userModules()
    {
        return $this->hasMany(UserModule::class);
    }

    /**
     * Get the modules through user modules.
     */
    public function modules()
    {
        return $this->belongsToMany(Module::class, 'user_modules')
            ->withPivot([
                'can_create_users',
                'can_edit_users',
                'can_delete_users',
                'can_reset_passwords',
                'can_assign_modules',
                'can_view_reports',
                'can_mark_salary_paid',
                'can_mark_salary_pending',
                'can_view_salary_data',
                'can_manage_salary_payments'
            ]);
    }

    /**
     * Check if user is super admin.
     */
    public function isSuperAdmin()
    {
        return $this->role_id == 1;
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin()
    {
        return $this->role_id == 2;
    }

    /**
     * Check if user is regular user.
     */
    public function isUser()
    {
        return $this->role_id == 3;
    }

    /**
     * Check if user is supervisor.
     */
    public function isSupervisor()
    {
        return $this->role_id == 7; // Supervisor role_id is 7
    }

    /**
     * Get the admin that manages this user.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get all users managed by this admin.
     */
    public function managedUsers()
    {
        return $this->hasMany(User::class, 'admin_id');
    }

    /**
     * Check if user has permission for a specific module.
     */
    public function hasPermission($moduleId, $permission)
    {
        // Admin and SuperAdmin always have access
        if ($this->isAdmin() || $this->isSuperAdmin()) {
            return true;
        }

        // Check user's module permissions
        $userModule = $this->userModules()
            ->where('module_id', $moduleId)
            ->first();

        if ($userModule && isset($userModule->$permission)) {
            return $userModule->$permission;
        }

        return false;
    }

    /**
     * Finance-specific permission methods
     */
    public function canMarkSalaryPaid($moduleId)
    {
        return $this->hasPermission($moduleId, 'can_mark_salary_paid');
    }

    public function canMarkSalaryPending($moduleId)
    {
        return $this->hasPermission($moduleId, 'can_mark_salary_pending');
    }

    public function canViewSalaryData($moduleId)
    {
        return $this->hasPermission($moduleId, 'can_view_salary_data');
    }

    public function canManageSalaryPayments($moduleId)
    {
        return $this->hasPermission($moduleId, 'can_manage_salary_payments');
    }

    public function canCreateUsers($moduleId)
    {
        return $this->hasPermission($moduleId, 'can_create_users');
    }

    public function canEditUsers($moduleId)
    {
        return $this->hasPermission($moduleId, 'can_edit_users');
    }

    public function canDeleteUsers($moduleId)
    {
        return $this->hasPermission($moduleId, 'can_delete_users');
    }

    public function canViewReports($moduleId)
    {
        return $this->hasPermission($moduleId, 'can_view_reports');
    }

    /**
     * Get the user who created this user
     */
    public function createdBy()
    {
        // Get the creator from userInfo table
        return $this->hasOneThrough(
            User::class,
            UserInfo::class,
            'user_id', // Foreign key on user_infos table
            'id', // Foreign key on users table
            'id', // Local key on users table
            'created_by_id' // Local key on user_infos table
        );
    }

    /**
     * Get salary payments for this user
     */
    public function salaryPayments()
    {
        return $this->hasMany(SalaryPayment::class);
    }
}