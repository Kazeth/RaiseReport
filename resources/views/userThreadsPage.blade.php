@extends('app')

@section('title', 'RaiseReport - My Threads')

@section('content')

    <body>
        <div class="col d-flex justify-content-center">
            <div class="row container-sm justify-content-center" style="width: 50vw;">
                <h1 class="d-flex justify-content-center">My Threads</h1>

                <input type="text" id="searchBox" class="form-control" placeholder="Search threads...">
                <div id="threadContainer">
                    @foreach ($threads as $thread)
                        @php
                            // Warna badge
                            $badgeColor = match ($thread->threadStatus) {
                                'Pending' => '#d39e00',
                                'Rejected' => '#cc0000',
                                default => '#28a745',
                            };

                            // Warna background card
                            $cardBg = match ($thread->threadStatus) {
                                'Pending' => '#fff7d1', // kuning muda
                                'Rejected' => '#ffe1e1', // merah muda
                                default => '#00FF0062', // approved putih
                            };
                        @endphp

                        <a href="{{ route('detail', $thread->id) }}?from={{ url()->current() }}"
                            style="text-decoration:none; color:inherit;">
                            <div class="card row thread-card"
                                style="padding: 1vw; margin: 2vw; background-color: {{ $cardBg }};">

                                {{-- Thread Name + Status Badge --}}
                                <div class="fw-bold mb-2 d-flex align-items-center" style="font-size: 1.2rem; gap: 10px;">
                                    {{ $thread->threadName }}

                                    <span
                                        style="
                    background-color: {{ $badgeColor }};
                    color: white;
                    padding: 4px 10px;
                    border-radius: 6px;
                    font-size: 0.8rem;
                ">
                                        {{ $thread->threadStatus }}
                                    </span>
                                </div>

                                {{-- Thread Content --}}
                                <div class="mb-3" style="text-align: justify;">
                                    {{ $thread->threadContent }}
                                </div>

                                {{-- Info --}}
                                <div class="d-flex justify-content-between mb-2">
                                    <div>Upvote : {{ $thread->upvotes_count }}</div>
                                    <div>Posted on {{ $thread->created_at->translatedFormat('d F Y - H.i') }}</div>
                                </div>


                                @if ($thread->upvotes->where('user_id', auth()->id())->isNotEmpty())
                                    {{-- Sudah pernah upvote --}}
                                    <button class="btn btn-secondary w-100 mt-2" disabled>
                                        ✓ Upvoted
                                    </button>
                                @elseif($thread->threadStatus === 'Approved')
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
