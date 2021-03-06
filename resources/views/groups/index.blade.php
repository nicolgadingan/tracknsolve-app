@extends('layouts.app')

@section('page')
    Groups
@endsection

@section('content')
<div class="container-fluid">
    @livewire('groups-index', ['configs' => $configs])

    {{-- Delete Group Form --}}
    <div class="modal fade" id="gr-delt-group-modal" tabindex="-1" aria-labelledby="gr-delt-group-label"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="gr-delt-group-form" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-flex justify-content-between p-3 align-items-center">
                        <h5 class="modal-title" id="gr-delt-group-label"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="p-4 pt-0 fs-re fg-danger">
                        Deleted groups will not be recovered. <br>
                        Are you sure you want to proceed deleting <b id="gr-delt-name"></b>?
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
</div>
@endsection