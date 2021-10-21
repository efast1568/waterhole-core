@props(['feed', 'channel' => null])

@php
    $posts = $feed->posts();
@endphp

@if ($posts->isNotEmpty())
    <div class="post-{{ $feed->currentLayout() }}">
        @foreach ($posts as $post)
            @if ($showLastVisit && $post->last_activity_at < session('previously_seen_at'))
                @once
                    @if (! $loop->first)
                        <div class="divider feed__last-visit-divider">Last Visit</div>
                    @endif
                @endonce
            @endif

            <x-dynamic-component
                :component="'waterhole::post-'.$feed->currentLayout().'-item'"
                :post="$post"
            />
        @endforeach
    </div>

    {{ $posts->withQueryString()->links() }}
@else
    <div class="placeholder">
        <x-heroicon-o-chat-alt-2 class="placeholder__visual"/>
        <h3>No Posts</h3>
    </div>
@endif
