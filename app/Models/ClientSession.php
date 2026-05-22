<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ClientSession extends Model
{
    protected $fillable = ['client_id', 'photo_path', 'session_date'];

    protected $table = 'client_sessions';

    // Добавьте этот массив:
    protected $casts = [
        'session_date' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(SessionFieldValue::class, 'client_session_id');
    }
}