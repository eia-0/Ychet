<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class ClientSession extends Model
{
    protected $fillable = [
        'client_id',
        'photo_path',      // оставлено для старых записей
        'photo_before',    // новое поле «до»
        'photo_after',     // новое поле «после»
        'session_date',
    ];

    protected $table = 'client_sessions';

    protected $casts = [
        'session_date' => 'datetime',
    ];

    protected static function booted()
    {
        static::deleting(function (ClientSession $session) {
            // удаляем старые одиночные фото
            if ($session->photo_path) {
                Storage::disk('public')->delete($session->photo_path);
            }
            // удаляем фото «до»
            if ($session->photo_before) {
                Storage::disk('public')->delete($session->photo_before);
            }
            // удаляем фото «после»
            if ($session->photo_after) {
                Storage::disk('public')->delete($session->photo_after);
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