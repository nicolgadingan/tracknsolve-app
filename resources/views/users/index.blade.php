@extends('layouts.app')

@section('page')
    Users
@endsection

@section('content')
<script>
    $(document).ready(function() {
        $("body").on("click", ".us-delete", function() {
            $("#us-delete-form").attr("action", "/users/" + $(this).attr("data-id"));
            $("#us-delete-form").submit();
        });
    });
</script>
<div class="container-fluid">
    @php
        $uinf    =   auth()->user();
    @endphp
    <div class="row mb-4">
        @if ($uinf->role == 'admin')
        <div class="col-sm right">
            <a href="/users/create" class="btn btn-marine shadow">
                New User
            </a>
        </div>
        @endif
    </div>
    @include('plugins.messages')
    <div class="row">
        <div class="col">
            @livewire('users-search-user')
        </div>
    </div>
</div>
@endsection