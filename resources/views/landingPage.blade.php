@extends('app')

@section('title', 'RaiseReport - Threads')

@section('content')

    <body>
        <div class="col d-flex justify-content-center">
            <div class="row container-sm justify-content-center" style="width: 50vw;">
                <h1 class="d-flex justify-content-center">Trendings</h1>

                <input type="text" id="searchBox" class="form-control" placeholder="Search threads...">
                <div id="threadContainer">
                    @foreach ($threads as $thread)
                        <a href="{{ route('detail', $thread->id) }}" style="text-decoration:none; color:inherit;">
                            <div class="card row" style="padding: 1vw; margin: 2vw;">

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
                                    <div>Posted on {{ $thread->created_at->translatedFormat('d F Y - H.i') }}</div>
                                </div>
                                @if ($thread->upvotes->isNotEmpty())
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
                            </div>
                        </a>
                    @endforeach
                </div>

            </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $('#searchBox').on('keyup', function() {
                let search = $(this).val();

                $.ajax({
                    url: "{{ route('searchDateSorted') }}",
                    type: "GET",
                    data: {
                        search: search
                    },
                    success: function(data) {

                        $('#threadContainer').html('');

                        data.forEach(thread => {
                            let isUpvoted = thread.upvotes.length > 0;

                            $('#threadContainer').append(`
            <div class="card row" style="padding: 1vw; margin: 2vw;">

                <div class="fw-bold mb-2" style="font-size: 1.2rem;">
                    ${thread.threadName}
                </div>

                <div class="mb-3" style="text-align: justify;">
                    ${thread.threadContent}
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <div>Upvote : ${thread.upvotes_count}</div>
                    <div>Posted on ${new Date(thread.created_at).toLocaleString()}</div>
                </div>

                ${isUpvoted
                    ? `<button class="btn btn-secondary w-100 mt-2" disabled>✓ Upvoted</button>`
                    : `<form action="/upvote/${thread.id}" method="POST" class="mt-2" style="max-width: 120px;">
                                            @csrf
                                            <button class="btn btn-success w-100">Upvote</button>
                                       </form>`
                }
            </div>
        `);
                        });
                    }

                });
            });
        </script>
    </body>
@endsection
