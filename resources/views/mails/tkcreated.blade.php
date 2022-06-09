@extends('layouts.email')

@section('content')
    @php
        $extn       =   '';
        $icon       =   '';

        if ($mail->ticket['assignee'] == $mail->user['uid']) {
            $extn   =   '';
            $icon   =   'warn';
        } else {
            $extn   =   'r group';
            $icon   =   'plus';
        }
        
    @endphp
    <div style="display: flex; align-items: center; justify-content: space-between;">
        <span class="left" style="font-size: large;">
          <b>{{ $mail->ticket['tkey'] }}</b><br>
          Ticket is assigned to you{{ $extn }}.
        </span>
        <img height="100px" src="{{ $mail->baseURL . '/imgs/email-' . $icon }}.svg" alt="Email logo">
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
        <a href="{{ $mail->baseURL . '/tickets/' . $mail->ticket['tkey'] . '/edit' }}" class="link-button">
            View Ticket
        </a>
    </div>
@endsection