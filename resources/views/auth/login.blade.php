@extends('layouts.auth')

@section('page')
    Login
@endsection

@section('content')
    <div class="d-flex justify-content-center">
        <div class="ts-card border-bubble pb-2" id="box-login">
            <div class="center mb-3 p-2 brand">
                @include('plugins.tnsicon')
                @include('plugins.tnstext')
            </div>
            @include('plugins.messages')
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input type="text" id="li-username" name="username" placeholder="Username" value="{{ old('username') }}"
                        class="form-control center borderless @error('username') is-invalid @enderror">
                    @error('username')
                        <span class="invalid-feedback" role="alert">
                            <span>{{ $message }}</span>
                        </span>
                    @enderror
                    <label for="li-username">Username</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" id="li-password" class="form-control center borderless @error('username') is-invalid @enderror"
                        name="password" placeholder="Password">
                    <label for="li-password">Password</label>
                </div>
                <div class="mb-3 pl-3">
                    <a href="{{ route('password.request') }}" class="link-main">Forgot password?</a>
                </div>
                <div class="right">
                    <button type="submit" class="btn btn-lg btn-main shadow">Login</button>
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