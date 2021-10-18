<?php

namespace Waterhole\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;
use Waterhole\Models\Post;

class MarkAsRead extends Action
{
    public function name(): string
    {
        return 'Mark as Read';
    }

    public function label(Collection $items): string|HtmlString
    {
        return 'Mark as Read';
    }

    public function icon(Collection $items): ?string
    {
        return 'heroicon-s-check';
    }

    public function appliesTo($item): bool
    {
        return $item instanceof Post && ($item->is_unread ?? true);
    }

    public function run(Collection $items, Request $request)
    {
        $items->each(function ($post) use ($request) {
            $post->userState->read()->save();
            $post->is_unread = false;
        });

        if ($request->wantsTurboStream()) {
            return response()->turboStreamView('waterhole::posts.stream-mark-as-read', ['posts' => $items]);
        }
    }
}