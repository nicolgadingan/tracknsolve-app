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
            @if (session('status') != "")
                @include('plugins.messages')
            @else
                <div class="alert alert-navy fs-sm">
                    <span>Hey there!</span><br>
                    {!! $info !!}
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                <div class="form-floating mb-3">
                    <input id="rp-email" type="email" class="form-control @error('email') is-invalid @enderror borderless center"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                    <label for="rp-email">Email</label>
                </div>
                <div class="mb-3 pl-3">
                    <a href="{{ route('login') }}" class="link-pumpkin">Login instead?</a>
                </div>
                <div class="right">
                    <button type="submit" class="btn btn-lg btn-pumpkin shadow">Reset</button>
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