<div class="card stack gap-lg full-height">
    <h3>
        <a
            href="{{ $feed->link }}"
            class="with-icon"
            target="_blank"
            rel="noopener"
        >
            <x-waterhole::icon icon="heroicon-o-rss"/>
            {{ $feed->title }}
        </a>
    </h3>

    @foreach ($feed->item as $item)
        @continue ($loop->index >= $limit)

        <article class="stack gap-xxs overlay-container">
            <a
                href="{{ $item->url }}"
                class="h5 color-accent block with-overlay"
                target="_blank"
                rel="noopener"
            >{{ $item->title }}</a>

            <p class="color-muted text-xxs">
                {{ Waterhole\relative_time(new DateTime('@'.$item->timestamp)) }}
                — {{ Str::limit($item->description, 200) }}
            </p>
        </article>
    @endforeach
</div>
