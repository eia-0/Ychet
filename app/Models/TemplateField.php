<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateField extends Model
{
    protected $fillable = ['user_id', 'name', 'type', 'sort_order'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function sessionFieldValues(): HasMany
    {
        return $this->hasMany(SessionFieldValue::class);
    }
}