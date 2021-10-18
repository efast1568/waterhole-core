<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChannelUser extends Model
{
    public $timestamps = false;

    protected $table = 'channel_user';

    protected $casts = [
        'marked_read_at' => 'datetime',
    ];

    public function markAsRead()
    {
        $this->marked_read_at = now();

        return $this;
    }

    public function channel(): BelongsTo
    {
        return $this->belongsTo(Channel::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function setKeysForSaveQuery($query)
    {
        return $query
            ->where('channel_id', $this->channel_id)
            ->where('user_id', $this->user_id);
    }
}