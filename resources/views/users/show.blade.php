@extends('layouts.app')

@section('page')
    {{ $user->first_name . ' ' . $user->last_name }}
@endsection

@section('content')
<div class="container">
    <div class="card card-body border-round border-forest-light pt-4">
        @livewire('users-edit', ['user' =>  $user])
    </div>
</div>
@endsection