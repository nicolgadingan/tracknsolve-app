@extends('layouts.email')

@section('content')
    <div>
        <img height="100px" src="{{ $mailData->baseURL . '/imgs/email-notice.svg' }}" alt="Track N' Solve logo">
    </div>
    <span style="font-size: x-large;">Email Verification!</span><br><br> 
    <p>
        You’ve received this message because your email address has been registered with tracknsolve.com.
    </p>
    <p>
        Please click the button below to verify your email address and confirm your password to complete your registration.
    </p>
    <p style="color: #0d6efd;">
        Please note, your username is <b>{{ $mailData->user->username }}</b>. 
    </p>
    <div class="p-2">
        <a href="{{ $mailData->baseURL . '/user/verify/' . $mailData->user->emailVerify->token }}" class="link-button">
            Verify Now
        </a>
    </div>
    <p>
        If you did not register with us, please disregard this email.
    </p>
@endsection