<?php

namespace App\Models;

// Импорты, которые уже есть:
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany; // добавьте эту строку

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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

    // >>> ДОБАВЬТЕ ЭТИ МЕТОДЫ <<<
    public function templateFields(): HasMany
    {
        return $this->hasMany(TemplateField::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }
}