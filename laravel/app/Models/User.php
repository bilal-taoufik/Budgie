<?php

namespace App\Models;

use Illuminate\Auth\MustVerifyEmail as MustVerifyEmailTrait;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmailContract
{
    use HasFactory, MustVerifyEmailTrait, Notifiable;

    protected $table = 'client';

    protected $fillable = [
        'nom',
        'prenom',
        'email',
        'tel',
        'mdp',
    ];

    protected $hidden = [
        'mdp',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'mdp' => 'hashed',
        ];
    }

    public function getAuthPasswordName(): string
    {
        return 'mdp';
    }

    public function comptes(): HasMany
    {
        return $this->hasMany(Compte::class, 'cmp_client_id');
    }
}
