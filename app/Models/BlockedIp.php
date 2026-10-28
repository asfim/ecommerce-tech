<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BlockedIp extends Model
{
    protected $fillable = [
        'ip_address',
        'reason',
        'blocked_by',
    ];

    public function blocker(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'blocked_by');
    }

    public static function isBlocked(?string $ip): bool
    {
        if (empty($ip)) {
            return false;
        }

        return static::where('ip_address', $ip)->exists();
    }
}
