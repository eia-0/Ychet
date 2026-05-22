<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $fillable = ['user_id', 'last_name', 'first_name', 'middle_name', 'phone'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(ClientSession::class);
    }

    // Получить последний сеанс (используется для предзаполнения и отображения)
    public function latestSession()
    {
        return $this->hasOne(ClientSession::class)->latestOfMany('session_date');
    }
}