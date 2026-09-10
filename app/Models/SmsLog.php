<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SmsLog extends Model
{
    /** @var list<string> */
    protected $fillable = [
        'api_token_id',
        'source',
        'type',
        'recipient',
        'recipient_count',
        'message',
        'success',
        'message_id',
        'raw_response',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'success' => 'boolean',
        'raw_response' => 'array',
        'recipient_count' => 'integer',
    ];

    public function apiToken(): BelongsTo
    {
        return $this->belongsTo(ApiToken::class);
    }
}
