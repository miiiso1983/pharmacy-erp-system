<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class MasterAdmin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'is_active',
        'permissions',
        'last_login_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'permissions' => 'array',
        'last_login_at' => 'datetime',
    ];

    // العلاقات
    public function createdLicenses()
    {
        return $this->hasMany(SystemLicense::class, 'created_by');
    }

    // Accessors
    public function getIsActiveStatusAttribute()
    {
        return $this->is_active ? 'نشط' : 'غير نشط';
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    // Methods
    public function updateLastLogin()
    {
        $this->update(['last_login_at' => now()]);
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->permissions ?? []);
    }

    public function activate()
    {
        $this->update(['is_active' => true]);
    }

    public function deactivate()
    {
        $this->update(['is_active' => false]);
    }

    public static function createMasterAdmin($data)
    {
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'is_active' => true,
            'permissions' => $data['permissions'] ?? [
                'manage_licenses',
                'view_statistics',
                'manage_admins',
                'system_settings'
            ]
        ]);
    }
}
