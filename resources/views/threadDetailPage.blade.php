@extends('app')

@section('title', 'RaiseReport - Thread Detail')

@section('content')

    <body>
        <div class="container mt-5" style="max-width: 700px;">

            <div class="d-flex justify-content-between align-items-center mb-3">
                <!-- Back Button -->
                <a href="{{ request('from') ?? route('threads') }}" class="btn btn-secondary">
                    ← Back
                </a>

                <div class="d-flex gap-2">
                    @if (auth()->check() && auth()->id() === $thread->userId)
                        <a href="{{ route('editThread', $thread->id) }}" class="btn btn-warning">
                            ✏ Edit Thread
                        </a>
                    @endif

                    @if (auth()->check() && (auth()->id() === $thread->userId || auth()->user()->role === 'admin'))
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteThreadModal">
                            🗑 Delete
                        </button>
                    @endif

                </div>


            </div>


            @php
                $cardBg = match ($thread->threadStatus) {
                    'Pending' => '#fff7d1', // kuning muda
                    'Rejected' => '#ffe1e1', // merah muda
                    default => '#ffffff', // approved putih
                };

            @endphp
            <!-- Thread Card -->
            <div class="card shadow-sm p-4 " style="background-color:{{ $cardBg }};">

                <h2 class="fw-bold d-flex align-items-center gap-2">
                    {{ $thread->threadName }}

                    @if ($thread->threadStatus === 'Pending' && auth()->check() && auth()->user()->role === 'user')
                        <span class="info-dot custom-tip">
                            i
                            <span class="custom-tooltip">
                                This thread is currently under review.<br>
                                It may have been pulled back by an admin,<br>
                                even if it was previously approved.
                            </span>
                        </span>
                    @endif


                    @if ($thread->threadStatus === 'Rejected' && auth()->user()->role === 'user')
                        <span class="info-dot custom-tip">
                            i
                            <span class="custom-tooltip">
                                This thread was rejected.<br>
                                Editing will resubmit it for review.
                            </span>
                        </span>
                    @endif
                </h2>



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
        <style>
            /* === ICON === */
            .info-dot {
                display: inline-flex;
                justify-content: center;
                align-items: center;

                width: 22px;
                height: 22px;

                border: 2px solid #bfbfbf;
                /* abu */
                border-radius: 50%;
                background: transparent;
                color: #bfbfbf;

                font-size: 14px;
                font-weight: bold;
                line-height: 1;

                cursor: pointer;
                position: relative;
                /* penting untuk tooltip */
            }

            .info-dot:hover {
                border-color: #999;
                color: #999;
                transition: 0.2s;
            }

            /* === CUSTOM TOOLTIP === */
            .custom-tooltip {
                visibility: hidden;
                opacity: 0;

                position: absolute;
                top: 50%;
                left: 32px;
                /* posisi tooltip di kanan ikon */
                transform: translateY(-50%);

                background: #333;
                color: #fff;
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 0.85rem;
                line-height: 1.3;
                width: max-content;
                max-width: 300px;

                transition: 0.2s ease;
                z-index: 999;
                pointer-events: none;
            }

            /* segitiga kecil */
            .custom-tooltip::after {
                content: "";
                position: absolute;
                left: -6px;
                top: 50%;
                transform: translateY(-50%);
                border-width: 6px;
                border-style: solid;
                border-color: transparent #333 transparent transparent;
            }

            /* show on hover */
            .custom-tip:hover .custom-tooltip {
                visibility: visible;
                opacity: 1;
                transform: translate(5px, -50%);
            }
        </style>
        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteThreadModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">

                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title">⚠ Delete Thread</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2">
                            Are you sure you want to delete this thread?
                        </p>
                        <p class="text-danger fw-bold mb-0">
                            This action cannot be undon3e.
                        </p>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                        <form id="deleteThreadForm" action="{{ route('thread.delete', $thread->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">
                                Yes, Delete
                            </button>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        <style>
            .modal-content {
                border-radius: 12px;
            }

            .modal-header {
                border-top-left-radius: 12px !important;
                border-top-right-radius: 12px !important;
            }
        </style>

    </body>
@endsection
