<?php

namespace Waterhole\View\Components;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;
use Waterhole\Extend\PostFeedQuery;
use Waterhole\Feed\PostFeed;
use Waterhole\Models\Channel;
use Waterhole\Models\Post;
use Waterhole\View\Components\Concerns\Streamable;

class PostFeedPinned extends Component
{
    use Streamable;

    public Collection $posts;

    public function __construct(public PostFeed $feed, public ?Channel $channel = null)
    {
        $query = Post::where('is_pinned', true)->whereNot->ignoring();

        if ($channel) {
            $query->whereBelongsTo($channel);
        } else {
            $query->whereHas('channel', fn($query) => $query->whereNot->ignoring());
        }

        foreach (PostFeedQuery::values() as $scope) {
            $scope($query);
        }

        $this->posts = $query->latest()->get();
    }

    public function render()
    {
        return $this->view('waterhole::components.post-feed-pinned');
    }
}