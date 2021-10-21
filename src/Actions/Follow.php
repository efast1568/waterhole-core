<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Waterhole\Models\Post;

class Follow extends Action
{
    public function name(): string
    {
        return 'Follow';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-o-bell';
    }

    public function appliesTo($item): bool
    {
        return method_exists($item, 'follow') && ! $item->isFollowed();
    }

    public function run(Collection $items, Request $request)
    {
        $items->each->follow();

        if ($request->wantsTurboStream() && $items[0] instanceof Post) {
            return response()->turboStreamView('waterhole::posts.stream-updated', ['posts' => $items]);
        }
    }
}
