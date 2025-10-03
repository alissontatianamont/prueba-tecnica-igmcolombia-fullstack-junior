<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
       use HasApiTokens, Notifiable, HasRoles;
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
     protected $guard_name = 'sanctum';

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'inv_user_id');
    }

    public function hasInvoices(): bool
    {
        return $this->invoices()->exists();
    }

}
