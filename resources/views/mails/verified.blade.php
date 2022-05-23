@extends('layouts.email')

@section('content')
    <div>
        <img height="100px" src="{{ asset('imgs/email-notice.svg') }}" alt="Yortik logo">
    </div>
    <span class="center" style="font-size: x-large;">Account Verified!</span><br><br> 
    <p>
        Thank you for verifying your account.
    </p>
    <p>
        Login to your account and start your journey with yortik. 
    </p>
    <div class="p-2">
        <a href="{{ Request::root() }}/login" class="link-button">
            Login
        </a>
    </div>
    <p>
        Forgot your username? It's <b>{{ $mailData->username }}</b>.<br>
        You can keep this email for later.
    </p>
@endsection