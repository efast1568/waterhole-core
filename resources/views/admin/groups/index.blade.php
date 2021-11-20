<x-waterhole::admin title="Groups">
    <div class="stack-md">
        <div class="toolbar toolbar--right">
            <a href="{{ route('waterhole.admin.groups.create') }}" type="button" class="btn btn--primary">
                <x-waterhole::icon icon="heroicon-s-plus"/>
                <span>Create Group</span>
            </a>
        </div>

        <ul
            class="card admin-structure"
            role="list"
        >
            @foreach ($groups as $group)
                <li class="admin-structure__content toolbar">
                    @if ($group->is_public)
                        <x-waterhole::group-label :group="$group"/>
                    @else
                        {{ $group->name }}
                    @endif
                    <div class="spacer"></div>
                    <x-waterhole::action-menu :for="$group" placement="bottom-end"/>
                </li>
            @endforeach
        </ul>
    </div>
</x-waterhole::admin>
