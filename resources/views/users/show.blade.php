@extends('layouts.app')

@section('page')
    {{ $user->first_name . ' ' . $user->last_name }}
@endsection

@section('content')
<div class="container">
    <div class="ts-card">
        @livewire('users-edit', ['user' =>  $user])
    </div>
</div>
@endsection