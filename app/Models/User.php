<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'address',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Role Helpers ──────────────────────────

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isProductManager(): bool
    {
        return $this->role === 'product_manager';
    }

    public function isCustomer(): bool
    {
        return $this->role === 'customer';
    }

    public function getDashboardRoute(): string
    {
        return match($this->role) {
            'admin'           => 'admin.dashboard',
            'product_manager' => 'product_manager.dashboard',
            default           => 'customer.dashboard',
        };
    }

    // ── Relationships ──────────────────────────

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
