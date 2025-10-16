<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'module_id',
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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
