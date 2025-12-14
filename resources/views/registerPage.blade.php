@extends('app')

@section('title', 'RaiseReport - Register')

@section('content')

    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-4">

                    <div class="card shadow">
                        <div class="card-header text-center">
                            <h4>@lang('messages.create_account')</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('register.store') }}">
                                @csrf

                                <div class="mb-3">
                                    <label>@lang('messages.name')</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                        required>

                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required>

                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label>@lang('messages.password')</label>
                                    <input type="password" name="password" class="form-control" required>

                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label>@lang('messages.confirm_password')</label>
                                    <input type="password" name="password_confirmation" class="form-control" required>
                                </div>


                                <button class="btn btn-primary w-100">@lang('messages.register')</button>

                                <div class="text-center mt-3">
                                    <a href="{{ route('login') }}">@lang('messages.login_suggestion')</a>
                                </div>

                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </body>
@endsection
