<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SessionFieldValue extends Model
{
    protected $fillable = ['client_session_id', 'template_field_id', 'value'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(ClientSession::class, 'client_session_id');
    }

    public function templateField(): BelongsTo
    {
        return $this->belongsTo(TemplateField::class);
    }
}