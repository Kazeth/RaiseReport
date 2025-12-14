<div class="row align-items-center shadow p-3 mb-5 bg-body rounded"
    style="position: sticky; top: 0; z-index: 999; background:white; margin:0;">

    {{-- Logo --}}
    <div class="col-2 d-flex align-items-center">
        <a href="{{ route('home') }}">
            <img src="{{ asset('RaiseReport_NavLogo.png') }}" alt="RaiseReport Logo" style="height:40px;">
        </a>
    </div>

    {{-- Menu --}}
    <div class="col-6 d-flex align-items-center">
        <a href="{{ route('threads') }}" class="mx-3">{{ __('app.threads') }}</a>

        @auth
            @if (auth()->user()->role === 'user')
                <a href="{{ route('userThreads') }}" class="mx-3">
                    {{ __('app.my_threads') }}
                </a>
                <a href="{{ route('createThread') }}" class="mx-3">
                    {{ __('app.create_thread') }}
                </a>
                <a href='{{ route('profile') }}' class="mx-3">{{ __('app.profile') }}</a>
            @endif

            @if (auth()->user()->role === 'admin')
                <a href='{{ route('manageThread') }}' class="mx-3">{{ __('app.manage_threads') }}</a>
                <a href='{{ route('onHoldThreads') }}' class="mx-3">{{ __('app.on_hold_threads') }}</a>
            @endif
        @endauth
    </div>

    {{-- Auth --}}
    <div class="col-4 d-flex justify-content-end align-items-center">
        {{-- Language Switch --}}
        <div class="mx-3">
            <a href="{{ route('lang.switch', 'id') }}" class="mx-1">ID</a> |
            <a href="{{ route('lang.switch', 'en') }}" class="mx-1">EN</a>
        </div>

        @guest
            <a href="{{ route('register') }}" class="mx-2">{{ __('app.register') }}</a>
            <a href="{{ route('login') }}" class="mx-2">{{ __('app.login') }}</a>
        @else
            <span class="mx-3 fw-bold">{{ Auth::user()->name }}</span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger btn-sm">{{ __('app.logout') }}</button>
            </form>
        @endguest
    </div>
</div>

<style>
    a {
        text-decoration: none;
    }
</style>
