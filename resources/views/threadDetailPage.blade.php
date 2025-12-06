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

                <!-- Files Section -->
                <hr>

                {{-- FILE ATTACHMENTS --}}
                @if ($thread->files && $thread->files->count() > 0)
                    <h4 class="mt-4">Attachments</h4>

                    <div class="list-group mt-2">
                        @foreach ($thread->files as $file)
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <strong>{{ $file->fileName }}</strong>
                                    <p class="text-muted small mb-0">{{ strtoupper($file->extension) }}</p>
                                </div>

                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#previewModal"
                                    data-file-url="{{ asset('storage/' . $file->path) }}"
                                    data-file-type="{{ strtolower($file->extension) }}">
                                    View
                                </button>
                            </div>
                        @endforeach
                    </div>
                @endif



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
        <!-- File Preview Modal -->
        <div class="modal fade" id="previewModal" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">File Preview</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body text-center" id="modalPreviewBody">
                        <p class="text-muted">Loading preview...</p>
                    </div>

                </div>
            </div>
        </div>
        <script>
            const previewModal = document.getElementById('previewModal');

            previewModal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                const fileUrl = button.getAttribute('data-file-url');
                const fileType = button.getAttribute('data-file-type'); // jpg, png, pdf, mp4, etc.

                const modalBody = document.getElementById('modalPreviewBody');

                modalBody.innerHTML = "Loading...";

                // Handle image files
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileType)) {
                    modalBody.innerHTML = `
                <img src="${fileUrl}" class="img-fluid rounded shadow" alt="preview">
            `;
                }

                // Handle PDF
                else if (fileType === "pdf") {
                    modalBody.innerHTML = `
                <embed src="${fileUrl}" type="application/pdf" width="100%" height="600px" />
            `;
                }

                // Handle video
                else if (['mp4', 'webm'].includes(fileType)) {
                    modalBody.innerHTML = `
                <video id="videoPreview" controls preload="metadata" class="w-100 rounded shadow">
                    <source src="${fileUrl}" type="video/${fileType}">
                </video>
            `;

                    setTimeout(() => {
                        const vid = document.getElementById("videoPreview");
                        if (vid) vid.load(); // Force metadata load
                    }, 200);
                }


                // Handle audio
                else if (['mp3', 'wav'].includes(fileType)) {
                    modalBody.innerHTML = `
                <audio controls class="w-100 mt-3">
                    <source src="${fileUrl}">
                </audio>
            `;
                }

                // Unknown type
                else {
                    modalBody.innerHTML = `
                <p class="text-danger">Preview not available for this file.</p>
                <a href="${fileUrl}" class="btn btn-primary mt-2" download>Download File</a>
            `;
                }
            });
            
            previewModal.addEventListener('hidden.bs.modal', function() {
                const video = document.querySelector('#modalPreviewBody video');
                const audio = document.querySelector('#modalPreviewBody audio');

                if (video) {
                    video.pause();
                    video.currentTime = 0; // reset ke awal
                }

                if (audio) {
                    audio.pause();
                    audio.currentTime = 0; // reset ke awal
                }

                // Optional: clear modal content
                document.getElementById('modalPreviewBody').innerHTML = '<p class="text-muted">Loading preview...</p>';
            });
        </script>
    </body>
@endsection
