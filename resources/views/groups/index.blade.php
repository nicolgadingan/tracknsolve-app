@extends('layouts.app')

@section('page')
    Groups
@endsection

@section('content')
<div class="container-fluid">
    @livewire('groups-index')
    {{-- New Group Form --}}
    <div class="modal fade" id="gr-new-group-form" tabindex="-1" aria-labelledby="gr-new-group-label"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="d-flex justify-content-between p-3 align-items-center">
                    <h5 class="modal-title" id="gr-new-group-label">New Group</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @livewire('groups-create', ['managers' => $managers])
            </div>
        </div>
    </div>
    {{-- Edit Group Form --}}
    <div class="modal fade" id="gr-edit-group-form" tabindex="-1" aria-labelledby="gr-edit-group-label"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                @livewire('groups-edit', ['managers' => $managers])
            </div>
        </div>
    </div>
</div>
@endsection