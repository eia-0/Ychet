<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class ClientSession extends Model
{
    protected $fillable = ['client_id', 'photo_path', 'session_date'];
    protected $table = 'client_sessions';

    protected $casts = [
        'session_date' => 'datetime',
    ];

    // Автоматическое удаление файла при удалении модели
    protected static function booted()
    {
        static::deleting(function (ClientSession $session) {
            if ($session->photo_path) {
                Storage::disk('public')->delete($session->photo_path);
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function fieldValues(): HasMany
    {
        return $this->hasMany(SessionFieldValue::class, 'client_session_id');
    }
}