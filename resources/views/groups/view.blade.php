@extends('layouts.app')

@section('page')
    Group {{ $group->name }}
@endsection

@section('content')
    <div class="container">
        @livewire('groups-edit', ['gid' => $group->id])
    </div>
@endsection