@extends('app')

@section('title', 'RaiseReport - Threads')

@section('content')

    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-4">

                    <div class="card shadow">
                        <div class="card-header text-center">
                            <h4>Create Account</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register.store') }}">
                                @csrf

                                <div class="mb-3">
                                    <label>Name</label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Password</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                <div class="mb-3">
                                    <label>Confirm Password</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>

                                <button class="btn btn-primary w-100">Register</button>

                                <div class="text-center mt-3">
                                    <a href="{{ route('login') }}">Already have an account? Login</a>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </body>
@endsection
