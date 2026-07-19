<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\ResetPasswordNotification;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // Définir les attributs qui peuvent être assignés
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role',
        'email_verification_token',
        'email_verification_expires_at',
    ];
    

    // Définir la relation entre User et Account
    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }


    // Définir la relation entre User et Transaction via Account
    public function transactions(): HasManyThrough
    {
        return $this->hasManyThrough(Transaction::class, Account::class);
    }
}