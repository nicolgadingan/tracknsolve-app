@extends('layouts.email')

@section('content')
    <div>
        <img height="100px" src="{{ asset('imgs/email-notice.svg') }}" alt="Yortik logo">
    </div>
    <span style="font-size: x-large;">Email Verification!</span><br><br> 
    <p>
        You’ve received this message because your email address has been registered with yortik.com.
    </p>
    <p>
        Please click the button below to verify your email address and confirm your password to complete your registration.
    </p>
    <p style="color: #0d6efd;">
        Please note, your username is <b>{{ $mailData->username }}</b>. 
    </p>
    <div class="p-2">
        <a href="{{ Request::root() }}/user/verify/{{ $mailData->emailVerify->token }}" class="link-button">
            Verify Now
        </a>
    </div>
    <p>
        If you did not register with us, please disregard this email.
    </p>
@endsection