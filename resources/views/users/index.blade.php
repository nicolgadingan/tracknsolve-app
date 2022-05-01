@extends('layouts.app')

@section('page')
    Users
@endsection

@section('content')
    @livewire('users-search-user')
    <script>
        $(document).ready(function() {
            $("body").on("click", ".us-delete", function() {
                $("#us-delete-form").attr("action", "/users/" + $(this).attr("data-id"));
                $("#us-delete-form").submit();
            });
        });
    </script>
@endsection