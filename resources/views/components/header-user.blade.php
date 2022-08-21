@auth
    <ui-popup placement="bottom-end" style="margin-left: var(--space-xs)">
        <a href="{{ Auth::user()->url }}" class="btn btn--icon">
            <x-waterhole::avatar :user="Auth::user()"/>
            <ui-tooltip>{{ Auth::user()->name }}</ui-tooltip>
        </a>

        <ui-menu class="menu" hidden>
            <h3 class="menu-heading">{{ Auth::user()->name }}</h3>

            @components(Waterhole\Extend\UserMenu::build())

            {{-- Disable Turbo as a means of clearing out the Drive cache --}}
            <form action="{{ route('waterhole.logout') }}" method="POST" data-turbo="false">
                @csrf
                <x-waterhole::menu-item
                    icon="heroicon-o-logout"
                    :label="__('waterhole::user.log-out-link')"
                />
            </form>
        </ui-menu>
    </ui-popup>
@endauth
