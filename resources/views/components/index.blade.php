<div class="section container with-sidebar index-layout">
    <div class="sidebar sidebar--sticky stack gap-lg">
        @components(Waterhole\Extend\IndexSidebar::build())
    </div>

    <div>
        {{ $slot }}
    </div>
</div>
