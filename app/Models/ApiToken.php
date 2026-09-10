<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ApiToken extends Model
{
    /** @var list<string> */
    protected $fillable = ['name', 'token', 'last_used_at', 'revoked_at'];

    /** @var array<string, string> */
    protected $casts = [
        'last_used_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function isActive(): bool
    {
        return $this->revoked_at === null;
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereNull('revoked_at');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(SmsLog::class);
    }
}
