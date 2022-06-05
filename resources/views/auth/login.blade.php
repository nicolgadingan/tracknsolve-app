@extends('layouts.auth')

@section('page')
    Login
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="card card-body borderless shadow-sm border-bubble pb-2" id="box-login">
            <div class="center mb-3 p-2 brand">
                <img src="{{ asset('imgs/tns-icon.svg') }}" alt="Track N' Solve logo" id="brand-auth" class="icon">
                <span class="text fs-2l">
                    tracknsolve
                </span>
            </div>
            @include('plugins.messages')
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" id="li-username" name="username" placeholder="Username" value="{{ old('username') }}"
                        class="form-control center borderless @error('username') is-invalid @enderror">
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <label for="li-username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" id="li-password" class="form-control center borderless" name="password" placeholder="Password">
                    <label for="li-password">Password</label>
                </div>
                <div class="mb-3">
                    <a href="#" class="link-marine">Forgot password?</a>
                </div>
                <div class="right">
                    <button type="submit" class="btn btn-lg btn-marine shadow">Login</button>
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