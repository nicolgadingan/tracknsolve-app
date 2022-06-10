@extends('layouts.email')

@section('content')
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <span class="left" style="font-size: large;">
          <b>{{ $mail->ticket['ticket_id'] }}</b><br>
          Your ticket has been resolved.
        </span>
        <img height="100px" src="{{ $mail->baseURL }}/imgs/email-check.svg" alt="Email logo">
    </div>
    <p class="left">
        <b>Title</b><br>
        {{ $mail->ticket['title'] }}
    </p>
    <p class="left">
        <b>Description</b><br>
        {{ $mail->ticket['description'] }}
    </p>
    <div style="padding: 1rem 0rem 1.5rem 0rem;" class="left">
        <a href="{{ $mail->baseURL }}/tickets/{{ $mail->ticket['ticket_id'] }}/edit" class="link-button">
            View Ticket
        </a>
    </div>
@endsection