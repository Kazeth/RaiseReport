@extends('app')

@section('title', 'RaiseReport - Thread Detail')

@section('content')

    <body>
        <div class="container mt-5" style="max-width: 700px;">

            <!-- Back button -->
            <a href="{{ request('from') ?? route('threads') }}" class="btn btn-secondary mb-3">
                ← Back
            </a>

            @php
                $cardBg = match ($thread->threadStatus) {
                    'Pending' => '#fff7d1', // kuning muda
                    'Rejected' => '#ffe1e1', // merah muda
                    default => '#ffffff', // approved putih
                };

            @endphp
            <!-- Thread Card -->
            <div class="card shadow-sm p-4 " style="background-color:{{ $cardBg }};">

                <h2 class="fw-bold">{{ $thread->threadName }}</h2>

                <p class="text-muted">
                    Posted on {{ $thread->created_at->translatedFormat('d F Y - H.i') }}
                </p>

                <hr>

                <!-- Thread Content -->
                <p style="font-size: 1.1rem;">
                    {{ $thread->threadContent }}
                </p>

                <!-- Upvote Section -->
                <div class="mt-4 d-flex flex-column">
                    <div class="d-flex align-items-center mb-2">
                        <span class="fw-bold me-2">Upvotes:</span>

                        <span class="badge bg-primary" style="font-size: 1rem;">
                            {{ $thread->upvotes_count }}
                        </span>
                    </div>
                </div>

                @if (auth()->check())
                    @if (auth()->user()->role === 'admin')
                        {{-- Admin tidak bisa upvote, tidak tampil apa-apa --}}
                    @else
                        {{-- User biasa --}}
                        @if ($thread->threadStatus === 'Approved')
                            @if ($thread->upvotes->contains('user_id', auth()->id()))
                                <button class="btn btn-secondary w-100 mt-2" disabled>✓ Upvoted</button>
                            @else
                                <form action="{{ route('upvote', $thread->id) }}" method="POST" class="mt-2"
                                    style="max-width: 120px;">
                                    @csrf
                                    <button class="btn btn-success w-100">Upvote</button>
                                </form>
                            @endif
                        @endif
                    @endif
                @else
                    <form action="{{ route('upvote', $thread->id) }}" method="POST" class="mt-2"
                        style="max-width: 120px;">
                        @csrf
                        <button class="btn btn-success w-100">Upvote</button>
                    </form>
                @endif

                @if (auth()->check() && auth()->user()->role === 'admin')
                    {{-- Admin Only --}}
                    @if ($thread->threadStatus === 'Pending')
                        <div class="d-flex">
                            <form action="{{ route('approve', $thread->id) }}" method="POST" class="m-2"
                                style="max-width: 120px;">
                                @csrf
                                <button class="btn btn-success w-100">Approve</button>
                            </form>

                            <form action="{{ route('reject', $thread->id) }}" method="POST" class="m-2"
                                style="max-width: 120px;">
                                @csrf
                                <button class="btn btn-danger w-100">Reject</button>
                            </form>
                        </div>
                    @elseif ($thread->threadStatus === 'Approved')
                        <form action="{{ route('revert', $thread->id) }}" method="POST" class="m-2">
                            @csrf
                            <button class="btn btn-warning">Revert to Pending</button>
                        </form>
                    @endif

                @endif
            </div>
        </div>
    </body>
@endsection
