@extends('app')

@section('title', 'RaiseReport - Profile')

@section('content')

    <body>
        <div class="container mt-4">
            <h1 class="mb-4">Profile Page</h1>

            <!-- Alerts -->
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div style="max-width: 500px;">
                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('POST')

                    <!-- Email (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control bg-light text-muted" value="{{ auth()->user()->email }}"
                            readonly>
                    </div>

                    <!-- Edit Name -->
                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ auth()->user()->name }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Submit -->
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                </form>
            </div>
        </div>
    </body>
@endsection
