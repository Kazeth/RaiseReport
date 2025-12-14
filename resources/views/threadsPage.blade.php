@extends('app')

@section('title', 'RaiseReport - Threads')

@section('content')

    <body>
        <div class="col d-flex justify-content-center">
            <div class="row container-sm justify-content-center" style="width: 50vw;">
                <h1 class="d-flex justify-content-center">@lang('messages.latest_threads')</h1>

                <input type="text" id="searchBox" class="form-control" placeholder="@lang('messages.search_threads')">
                <div id="threadContainer">
                    @foreach ($threads as $thread)
                        <a href="{{ route('detail', $thread->id) }}?from={{ url()->current() }}" style="text-decoration:none; color:inherit;">
                            <div class="card row thread-card" style="padding: 1vw; margin: 2vw;">

                                {{-- Thread Name (full width) --}}
                                <div class="fw-bold mb-2" style="font-size: 1.2rem;">
                                    {{ $thread->threadName }}
                                </div>

                                {{-- Thread Content (full width, panjang) --}}
                                <div class="mb-3" style="text-align: justify;">
                                    {{ $thread->threadContent }}
                                </div>

                                {{-- Info Section --}}
                                <div class="d-flex justify-content-between mb-2">
                                    <div>Upvote : {{ $thread->upvotes_count }}</div>
                                    <div>@lang('messages.posted_on'){{ $thread->created_at->translatedFormat('d F Y - H.i') }}</div>
                                </div>
                                {{-- guest --}}
                                @guest
                                    <form action="{{ route('upvote', $thread->id) }}" method="POST" class="mt-2"
                                        style="max-width: 120px;">
                                        @csrf
                                        <button class="btn btn-success w-100">Upvote</button>
                                    </form>
                                @endguest


                                {{-- user --}}
                                @auth
                                    {{-- admin --}}
                                    @if (auth()->user()->role === 'admin')
                                        {{-- admin tidak bisa upvote (tidak tampil apa2) --}}
                                    @else
                                        {{-- user biasa --}}
                                        @if ($thread->upvotes->where('user_id', auth()->id())->isNotEmpty())
                                            {{-- Sudah pernah upvote --}}
                                            <button class="btn btn-secondary w-100 mt-2" disabled>
                                                ✓ Upvoted
                                            </button>
                                        @else
                                            {{-- Belum pernah upvote --}}
                                            <form action="{{ route('upvote', $thread->id) }}" method="POST" class="mt-2"
                                                style="max-width: 120px;">
                                                @csrf
                                                <button class="btn btn-success w-100">Upvote</button>
                                            </form>
                                        @endif
                                    @endif
                                @endauth
                            </div>
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            document.getElementById("searchBox").addEventListener("input", function() {
                const q = this.value.toLowerCase();

                document.querySelectorAll(".thread-card").forEach(card => {
                    const title = card.querySelector('.fw-bold').innerText.toLowerCase();
                    const content = card.querySelector('.mb-3').innerText.toLowerCase();

                    if (title.includes(q) || content.includes(q)) {
                        card.style.display = "";
                    } else {
                        card.style.display = "none";
                    }
                });
            });
        </script>
    </body>
@endsection
