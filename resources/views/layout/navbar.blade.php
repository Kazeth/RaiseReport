<div class="row align-items-center justify-content-start shadow p-3 mb-5 bg-body rounded"
    style="padding: 1vw; position: sticky; top: 0; z-index: 999; background: white;">

    <div class="row" style="margin-left: 1vw">
        <div class="col-2">
            <a href="{{ route('home') }}">RaiseReport</a>
        </div>
        <div class="col-6 d-flex justify-content-start">
            {{-- guest --}}
            <a href="{{ route('threads') }}" class="mx-3">Threads</a>

            @auth
                {{-- user --}}
                @if (auth()->user()->role === 'user')
                    <a href='{{ route('userThreads') }}' class="mx-3">My Threads </a>
                    <a href='{{ route('createThread') }}' class="mx-3">Create Thread</a>
                    <a href='{{ route('profile') }}' class="mx-3">Profile</a>
                @endif

                {{-- admin --}}
                @if (auth()->user()->role === 'admin')
                    <a href='{{ route('manageThread') }}' class="mx-3">Manage Threads</a>
                    <a href='{{ route('onHoldThreads') }}' class="mx-3">On-Hold Threads</a>
                @endif
            @endauth
        </div>
        <div class="col-4 d-flex justify-content-end align-items-center">

            @guest
                {{-- If user NOT logged in --}}
                <a href="{{ route('register') }}" class="mx-2">Register</a>
                <a href="{{ route('login') }}" class="mx-2">Login</a>
            @else
                {{-- If user IS logged in --}}
                <span class="mx-3 fw-bold">
                    {{ Auth::user()->name }}
                </span>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm">Logout</button>
                </form>
            @endguest

        </div>
    </div>



</div>
<style>
    a {
        text-decoration: none;
    }
</style>
