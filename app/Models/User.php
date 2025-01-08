<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',       // Hide password
        'remember_token', // Optionally hide remember token
    ];

    public function isManager()
    {
        return $this->attributes['role'] == UserRole::MANAGER;
    }

    public function isUser()
    {
        return $this->attributes['role'] == UserRole::USER;
    }

    public function getRoleName()
    {
        return UserRole::getStringValue($this->attributes['role']);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class, 'assigned_user_id');
    }

}
