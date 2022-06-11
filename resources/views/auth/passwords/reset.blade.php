@extends('layouts.auth')

@section('page')
    Reset Password
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="card card-body borderless shadow-sm border-bubble pb-2" id="box-login">
            <div class="center mb-3 p-2 brand">
                @include('plugins.tnsicon')
                @include('plugins.tnstext')
            </div>
            @include('plugins.messages')
            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-floating mb-3">
                    <input id="email" type="email" class="form-control borderless center @error('email') is-invalid @enderror"
                        name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" readonly>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror center borderless" name="password"
                        required autocomplete="new-password">
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <label for="password">Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input id="password-confirm" type="password" class="form-control center borderless"
                            name="password_confirmation" required autocomplete="new-password">
                    <label for="password-confirm">Confirm Password</label>
                </div>
                <div class="right pt-3">
                    <button type="submit" class="btn btn-lg btn-marine shadow">Confirm</button>
                </div>
            </form>
            <div class="pt-5 center">
                <small class="text-muted">
                    v{{ config('app.ver') }}
                </small>
            </div>
        </div>
    </div>
@endsection
