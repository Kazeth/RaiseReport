@extends('app')

@section('title', 'RaiseReport - Login')

@section('content')

    <body>
        <div class="container mt-5">
            <div class="row justify-content-center">
                <div class="col-md-4">

                    <div class="card shadow">
                        <div class="card-header text-center">
                            <h4>@lang('messages.login')</h4>
                        </div>

                        <div class="card-body">
                            <form method="POST" action="{{ route('login.process') }}">
                                @csrf

                                <div class="mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label>@lang('messages.password')</label>
                                    <input type="password" name="password" class="form-control" required>
                                </div>

                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <button class="btn btn-primary w-100">@lang('messages.login')</button>

                                <div class="text-center mt-3">
                                    <a href="{{ route('register') }}">@lang('messages.register_suggestion')</a>
                                </div>

                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </body>
@endsection
