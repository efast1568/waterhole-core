@auth
    <ui-popup
        placement="bottom-end"
        data-controller="notifications-popup"
        data-turbo-permanent
    >
        <a
            href="{{ route('waterhole.notifications.index') }}"
            class="btn btn--icon btn--transparent"
            data-action="notifications-popup#open"
        >
            <x-waterhole::icon icon="heroicon-o-bell"/>

            <span
                class="badge bg-activity"
                data-notifications-popup-target="badge"
                id="header-notifications-badge"
                @if (!$count = Auth::user()->unread_notification_count) hidden @endif
            >{{ $count }}</span>

            <ui-tooltip>Notifications</ui-tooltip>
        </a>

        <ui-menu hidden class="menu notifications-menu">
            {{--
                https://github.com/hotwired/turbo/pull/445#issuecomment-995305287
            --}}
            <turbo-frame
                data-id="notifications"
                data-controller="turbo-frame"
                src="{{ route('waterhole.notifications.index') }}"
                loading="lazy"
                data-notifications-popup-target="frame"
                disabled
            >
                <div class="loading"></div>
            </turbo-frame>
        </ui-menu>
    </ui-popup>
@endauth
