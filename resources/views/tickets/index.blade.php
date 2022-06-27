@extends('layouts.app')

@section('page')
    Tickets
@endsection

@section('content')
    @livewire('tickets-index', [
        'dueDays'   =>  $dueDays,
        // 'newRecord' =>  $newRecord
    ])
@endsection