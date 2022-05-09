@extends('layouts.app')

@section('page')
    Users
@endsection

@section('content')
    @livewire('users-search-user')
    <div class="modal fade" id="us-view-user-form" tabindex="-1" aria-labelledby="us-view-user-label"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="d-flex justify-content-between p-3 align-items-center">
                    <h5 class="modal-title" id="us-view-user-label"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete User Modal --}}
    <div class="modal fade" id="us-delt-user-modal" tabindex="-1" aria-labelledby="us-delt-user-label"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="us-delt-user-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-between p-3 align-items-center">
                        <h5 class="modal-title" id="us-delt-user-label"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="p-4 pt-0 fs-re fg-danger">
                        Deleted users will not be recovered.<br>
                        Are you sure you want to proceed deleting <b id="us-delt-user-name"></b>?
                    </div>
                    <div class="mb-1 p-4 pt-0 right">
                        <button type="button" class="btn btn-light mb-2 mr-2" data-bs-dismiss="modal" aria-label="Close">
                            Cancel
                        </button>
                        @if (auth()->user()->role == "admin")
                            <button type="submit" class="btn btn-danger shadow-sm mb-2">
                                Delete
                            </button>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("body").on("click", ".us-delete", function() {
                $("#us-delete-form").attr("action", "/users/" + $(this).attr("data-id"));
                $("#us-delete-form").submit();
            });

            $("body").on("click", ".us-view-trigger", function() {
                var modal   =   $("#us-view-user-form"),
                    label   =   $("#us-view-user-label");
            });
        });
    </script>
@endsection