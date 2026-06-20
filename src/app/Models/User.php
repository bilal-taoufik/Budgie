<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Les attributs qui sont assignables en masse
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role',
        'email_verified',
        'email_verification_token',
        'email_verification_expires_at',
    ];

    // Les attributs qui doivent être castés
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'email_verified' => 'boolean',
            'email_verification_expires_at' => 'datetime',
        ];
    }
}
