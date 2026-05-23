<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'timezone',
        'role',            // <-- добавляем
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

    // Проверка, является ли пользователь администратором
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function templateFields(): HasMany
    {
        return $this->hasMany(TemplateField::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

        public function templates(): HasMany
    {
        return $this->hasMany(Template::class);
    }
}