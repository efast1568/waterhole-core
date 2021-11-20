<?php

namespace Waterhole\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Waterhole\Models\Concerns\HasVisibility;
use Waterhole\Models\Concerns\ReceivesPermissions;

class Group extends Model
{
    use HasVisibility;
    use ReceivesPermissions;

    public const GUEST_ID = 1;
    public const MEMBER_ID = 2;

    public $timestamps = false;

    public function isGuest(): bool
    {
        return $this->id === static::GUEST_ID;
    }

    public function isMember(): bool
    {
        return $this->id === static::MEMBER_ID;
    }

    public function isCustom(): bool
    {
        return ! $this->isGuest() && ! $this->isMember();
    }

    public static function guest(): static
    {
        return (new static())->newInstance(['id' => static::GUEST_ID], true);
    }

    public static function member(): static
    {
        return (new static())->newInstance(['id' => static::MEMBER_ID], true);
    }

    public function scopeCustom(Builder $query)
    {
        $query->whereNotIn('id', [static::GUEST_ID, static::MEMBER_ID]);
    }

    public function getEditUrlAttribute(): string
    {
        return route('waterhole.admin.groups.edit', ['group' => $this]);
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->custom()->whereKey($value)->firstOrFail();
    }

    public static function rules(Group $group = null): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'is_public' => ['boolean'],
            'icon' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'regex:/^[a-f0-9]{3}|[a-f0-9]{6}$/i'],
            'permissions' => ['array'],
        ];
    }
}
