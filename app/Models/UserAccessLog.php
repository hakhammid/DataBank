<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccessLog extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'id_number',
        'usertype',
        'ip_address',
        'user_agent',
        'login_at',
        'last_seen_at',
        'logout_at',
    ];

    protected function casts(): array
    {
        return [
            'login_at' => 'datetime',
            'last_seen_at' => 'datetime',
            'logout_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
