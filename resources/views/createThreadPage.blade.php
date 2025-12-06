@extends('app')

@section('title', 'RaiseReport - Create Thread')

@section('content')
<body>
    <div class="container mt-5" style="max-width: 700px;">

        <h1 class="mb-4">Create New Thread</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('thread.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            {{-- Thread Name --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Thread Title</label>
                <input type="text" name="threadName" class="form-control" placeholder="Enter title"
                       required value="{{ old('threadName') }}">
            </div>

            {{-- Thread Content --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Description</label>
                <textarea name="threadContent" class="form-control" rows="6" placeholder="Describe your thread..."
                          required>{{ old('threadContent') }}</textarea>
            </div>

            {{-- File Upload --}}
            <div class="mb-3">
                <label class="form-label fw-bold">Attachments (optional)</label>
                <input type="file" name="files[]" class="form-control" multiple>
                <p class="text-muted small">Allowed: JPG, PNG, PDF, MP4, MP3, WAV</p>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-3">Submit Thread</button>
        </form>
    </div>
</body>
@endsection
