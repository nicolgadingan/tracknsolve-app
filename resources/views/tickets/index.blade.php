@extends('layouts.app')

@section('page')
    Tickets
@endsection

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-sm">
            
        </div>
        <div class="col-sm right">
            <a href="/tickets/create" class="btn btn-marine shadow">
                New Ticket
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col">
            @livewire('tickets-index')
        </div>
    </div>
</div>
@endsection