<?php

namespace Waterhole\Http\Controllers\Forum;

use Illuminate\Http\Request;
use Waterhole\Extend\CommentsSort;
use Waterhole\Http\Controllers\Controller;
use Waterhole\Models\Channel;
use Waterhole\Models\Comment;
use Waterhole\Models\Post;
use Waterhole\Sorts\Sort;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('waterhole.auth')->except('show');
        $this->middleware('waterhole.throttle:waterhole.create')->only('store', 'update');
    }

    public function show(Post $post, Request $request)
    {
        $this->authorize('view', $post);

        $post->load('likedBy');

        $sorts = CommentsSort::getInstances();
        $currentSort = $sorts->first(function (Sort $sort) use ($request) {
            return $sort->handle() === $request->query('sort');
        }, $sorts[0]);

        $query = $post->comments()->with(['user', 'parent.user', 'likedBy']);

        // $comment = $comments = null;

        // if ($cid = $request->query('comment')) {
        //     if (! $comment = $query->find($cid)) {
        //         abort(404);
        //         //return redirect($post->url);
        //     }
        //
        //     $all = $comment->descendantsAndSelf
        //         ->load('user', 'likedBy', 'parent.post', 'parent.user')
        //         ->each->setRelation('post', $post);
        //
        //     $comment = $all->toTree()[0];
        //     // $comment->setRelation('parent', null);
        //
        //     // $comment->setRelation('post', $post);//->load('replies.user', 'replies.likedBy');
        //     // $comment->replies->each->setRelation('parent', $comment);
        //     // $comment->replies->each->setRelation('post', $post);
        // } else {
            $currentSort->apply($query/*->orderBy('is_pinned', 'desc')*/);
            $comments = $query->paginate();
            $comments->getCollection()->each(function (Comment $comment) use ($post) {
                $comment->setRelation('post', $post);
                $comment->parent?->setRelation('post', $post);
            });
        // }

        // Mark the post as read for the current user
        // if ($comments) {
            $post->userState?->read($comments->lastItem())->save();
        // }

        return view('waterhole::posts.show', compact('post', 'comments', 'sorts', 'currentSort'));
    }

    public function create()
    {
        $this->authorize('create', Post::class);

        if ($channelId = request('channel')) {
            $channel = Channel::findOrFail($channelId);
        }

        return view('waterhole::posts.create', [
            'channel' => $channel ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorize('create', Post::class);

        if ($request->has('publish')) {
            $post = Post::byUser(
                $request->user(),
                $this->data($request)
            );

            $post->save();

            return redirect($post->url);
        }

        if ($request->has('channel_id')) {
            $channel = Channel::findOrFail($request->input('channel_id'));
        }

        return redirect()
            ->route('waterhole.posts.create', ['channel' => $channel->id ?? null])
            ->withInput();
    }

    public function edit(Post $post)
    {
        $this->authorize('update', $post);

        return view('waterhole::posts.edit', ['post' => $post]);
    }

    public function update(Post $post, Request $request)
    {
        $this->authorize('update', $post);

        $post->fill($this->data($request, $post))->wasEdited()->save();

        return redirect($request->get('return', $post->url));
    }

    private function data(Request $request, Post $post = null): array
    {
        $data = $request->validate(Post::rules($post));

        if (isset($data['channel_id'])) {
            $this->authorize('post', Channel::findOrFail($data['channel_id']));
        }

        return $data;
    }
}
