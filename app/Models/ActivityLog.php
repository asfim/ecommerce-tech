<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'user_type',
        'action',
        'description',
        'ip_address',
        'user_agent',
    ];

    public function user(): MorphTo
    {
        return $this->morphTo('user');
    }

    public static function log(string $action, ?string $description = null): void
    {
        $user = null;
        $userType = null;

        if (auth('admin')->check()) {
            $user = auth('admin')->user();
            $userType = get_class($user);
        } elseif (auth('web')->check()) {
            $user = auth('web')->user();
            $userType = get_class($user);
        }

        static::create([
            'user_id' => $user?->id,
            'user_type' => $userType,
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
