<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostUser extends Model
{
    public $timestamps = false;

    protected $table = 'post_user';

    protected $casts = [
        'last_read_at' => 'datetime',
        'followed_at' => 'datetime',
    ];

    public function read(?int $index): static
    {
        $this->last_read_at = now();
        $this->last_read_index = max($index, $this->last_read_index);

        return $this;
    }

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected function setKeysForSaveQuery($query)
    {
        return $query
            ->where('post_id', $this->post_id)
            ->where('user_id', $this->user_id);
    }
}
