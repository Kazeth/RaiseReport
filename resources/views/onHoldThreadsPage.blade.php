@extends('app')

@section('title', 'RaiseReport - On-Hold Threads')

@section('content')

    <body>
        <div class="col d-flex justify-content-center">
            <div class="row container-sm justify-content-center" style="width: 50vw;">
                <h1 class="d-flex justify-content-center">@lang('messages.on_hold_threads')</h1>

                <input type="text" id="searchBox" class="form-control" placeholder="@lang('messages.search_threads')">
                <div id="threadContainer">
                    @foreach ($threads as $thread)
                        <a href="{{ route('detail', $thread->id) }}?from={{ url()->current() }}" style="text-decoration:none; color:inherit;">
                            <div class="card row thread-card" style="padding: 1vw; margin: 2vw; background-color: #fff7d1;">

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
                                    <div>@lang('messages.posted_on'){{ $thread->created_at_formatted }}</div>
                                </div>
                                <div class="d-flex">
                                    <form action="{{ route('approve', $thread->id) }}" method="POST" class="m-2"
                                        style="max-width: 120px;">
                                        @csrf
                                        <button class="btn btn-success w-100">@lang('messages.approve')</button>
                                    </form>
                                    <form action="{{ route('reject', $thread->id) }}" method="POST" class="m-2"
                                        style="max-width: 120px;">
                                        @csrf
                                        <button class="btn btn-danger w-100">@lang('messages.reject')</button>
                                    </form>
                                </div>
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
