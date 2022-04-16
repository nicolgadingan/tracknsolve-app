@extends('layouts.app')

@section('page')
    New Ticket
@endsection

@section('content')
<div class="container-fluid">
    @include('plugins.previous')
    <div class="card card-body border-round border-forest-light pt-4">
        <h6 class="fg-forest">TICKET INFORMATION</h6>
        @include('plugins.messages')
        <div class="row">
            <div class="col">
                <div class="form-floating">
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection