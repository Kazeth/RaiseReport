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
        <a href="{{ route('threads') }}" class="mx-3">Threads</a>

        @auth
            @if (auth()->user()->role === 'user')
                <a href='{{ route('userThreads') }}' class="mx-3">My Threads</a>
                <a href='{{ route('createThread') }}' class="mx-3">Create Thread</a>
                <a href='{{ route('profile') }}' class="mx-3">Profile</a>
            @endif

            @if (auth()->user()->role === 'admin')
                <a href='{{ route('manageThread') }}' class="mx-3">Manage Threads</a>
                <a href='{{ route('onHoldThreads') }}' class="mx-3">On-Hold Threads</a>
            @endif
        @endauth
    </div>

    {{-- Auth --}}
    <div class="col-4 d-flex justify-content-end align-items-center">
        @guest
            <a href="{{ route('register') }}" class="mx-2">Register</a>
            <a href="{{ route('login') }}" class="mx-2">Login</a>
        @else
            <span class="mx-3 fw-bold">{{ Auth::user()->name }}</span>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-outline-danger btn-sm">Logout</button>
            </form>
        @endguest
    </div>
</div>

<style>
    a {
        text-decoration: none;
    }
</style>
