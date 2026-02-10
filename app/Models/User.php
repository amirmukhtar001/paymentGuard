<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use jeremykenedy\LaravelRoles\Traits\HasRoleAndPermission;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoleAndPermission, SoftDeletes;

    protected $connection = 'mysql';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $fillable = [
        'name',
        'designation',
        'email',
        'username',
        'password',
        'company_id',
        'section_id',
        'parent_id',
        'contact_number',
        'description',
        'status',
        'verified_by',
        'verified_at',
        'business_id',
        'role',
        'is_active',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'verified_at' => 'datetime',
        'otp_time' => 'datetime',
        'role' => UserRole::class,
        'is_active' => 'boolean',
    ];

    public function roles()
    {
        return $this->belongsToMany(MyRole::class, 'role_user', 'user_id', 'role_id')->withTimestamps();
    }

    public function permissions()
    {
        return $this->belongsToMany(MyPermission::class, 'permission_user', 'user_id', 'permission_id')->withTimestamps();
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function parent()
    {
        return $this->belongsTo(User::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(User::class, 'parent_id', 'id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id');
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function ownedBusinesses()
    {
        return $this->hasMany(Business::class, 'owner_id');
    }

    public function branches()
    {
        return $this->belongsToMany(Branch::class, 'branch_user')
            ->withPivot(['role', 'is_primary'])
            ->withTimestamps();
    }

    public function shiftsAsCashier()
    {
        return $this->hasMany(Shift::class, 'cashier_id');
    }

    public function shiftsAsManager()
    {
        return $this->hasMany(Shift::class, 'manager_id');
    }

    public function isOwner(): bool
    {
        return $this->role === UserRole::Owner;
    }

    public function isManager(): bool
    {
        return $this->role === UserRole::Manager;
    }

    public function isCashier(): bool
    {
        return $this->role === UserRole::Cashier;
    }
}
