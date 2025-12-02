@extends('app')

@section('title', 'RaiseReport - Thread Detail')

@section('content')

    <body>
        <div class="container mt-5" style="max-width: 700px;">

            <!-- Back button -->
            <a href="{{ route('threads') }}" class="btn btn-secondary mb-3">← Back</a>

            <!-- Thread Card -->
            <div class="card shadow-sm p-4">

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
                            {{ $thread->threadUpvote }}
                        </span>
                    </div>

                    <form action="{{ route('upvote', $thread->id) }}" method="POST" class="mt-2"
                        style="max-width: 120px;">
                        @csrf
                        <button class="btn btn-success w-100">Upvote 👍</button>
                    </form>
                </div>
            </div>
        </div>
    </body>
@endsection
