<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function userModules()
    {
        return $this->hasMany(UserModule::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_modules')
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
}
