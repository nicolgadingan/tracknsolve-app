@extends('layouts.auth')

@section('page')
    Verify Registration
@endsection

@section('content')
<div class="d-flex justify-content-center">
    <div class="ts-card-dark border-bubble pb-2" id="box-login">
        <div class="center mb-3 p-2 brand">
            @include('plugins.tnsicon')
            @include('plugins.tnstext')
        </div>
        @if ($status != "not-exists")
            @if ($status == "not-verified")
                <div class="ts-alert-dark ts-alert-primary">
                    <div class="justify">
                        {!! $message !!}
                    </div>
                </div>
                @include('plugins.messages')
                <form action="{{ route('verify') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="password" id="password" name="password" placeholder="Password"
                            class="form-control @error('password') is-invalid @enderror center" autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <label for="password">Password</label>
                    </div>
                    <div class="form-floating mb-4">
                        <input type="password" id="confirm-password" name="password_confirmation" placeholder="Confirm Password"
                            class="form-control @error('password_confirmation') is-invalid @enderror center" autocomplete="new-password">
                        @error('password_confirmation')
                            <span class="invalid-feedback" role="alert">
                                <strong>{!! $message !!}</strong>
                            </span>
                        @enderror
                        <label for="confirm-password">Confirm Password</label>
                    </div>
                    <input type="hidden" value="{{ $userid }}" name="user_id">
                    <div class="right">
                        <button type="submit" class="btn btn-lg btn-yellow shadow">Submit</button>
                    </div>
                </form>
            @else
                <div class="ts-alert-dark ts-alert-warning">
                    <div class="justify">
                        {!! $message !!}
                    </div>
                </div>
            @endif
        @else
        <div class="ts-alert-dark ts-alert-warning">
            <div class="justify">
                {!! $message !!}
            </div>
        </div>
        @endif
        <div class="pt-5 center">
            <small class="text-muted">
                v{{ config('app.ver') }}
            </small>
        </div>
    </div>
</div>
@endsection