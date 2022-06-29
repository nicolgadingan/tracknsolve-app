@extends('layouts.app')

@section('page')
    Create Group
@endsection

@section('content')
    @livewire('groups-create', ['managers' => $managers])
@endsection