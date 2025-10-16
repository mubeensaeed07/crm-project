<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'code',
        'is_active',
        'admin_id'
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean'
        ];
    }

    /**
     * Get the users in this department.
     */
    public function users()
    {
        return $this->hasManyThrough(User::class, UserInfo::class, 'department_id', 'id', 'id', 'user_id');
    }

    /**
     * Get the user infos in this department.
     */
    public function userInfos()
    {
        return $this->hasMany(UserInfo::class);
    }

    /**
     * Get the admin that manages this department.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
