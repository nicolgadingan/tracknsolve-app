@extends('layouts.app')

@section('page')
    View Incident
@endsection

@section('content')
@php
    $access =   auth()->user();
@endphp
<div class="container" id="tk-create-box">
    <div class="card card-body border-round border-forest-light pt-4">
        <div class="mb-2">
            @if ($ticket->group_id == $access->group_id &&
                                $ticket->assignee == '')
                <form action="/tickets/{{ $ticket->tkey }}/get" method="POST" id="tk-assigntome-form" class="d-none">
                    @csrf
                    @method('PUT')
                </form>
                <script>
                    $(document).ready(function() {

                        $("body").on("click", "#tk-assigntome", function() {
                            event.preventDefault();
                            $("#tk-assigntome-form").submit();
                        })

                    });
                </script>
            @endif
            <form method="POST" action="/tickets/{{ $ticket->tkey }}">
                @csrf
                @method('PUT')
                <div class="mb-4 d-flex justify-content-between">
                    @includeIf('plugins.previous', ['path' => '/tickets'])
                    <div class="d-flex">
                        @if ($ticket->group_id == $access->group_id &&
                                $ticket->assignee == '')
                            <div class="mr-3">
                                <button type="button" class="btn btn-lg btn-outline-secondary" id="tk-assigntome">
                                    Get
                                </button>
                            </div>
                        @endif
                        <button type="submit" class="btn btn-marine btn-lg shadow" id="tk-update-submit">
                            Update
                        </button>
                    </div>
                </div>
                @include('plugins.messages')
                <h6 class="fg-forest">STATUS</h6>
                <div class="row mb-3 g-3">
                    <div class="col-md">
                        <div class="form-floating mb-3">
                            <input type="text" name="tkey" class="form-control" id="tk-tkey" value="{{ $ticket->tkey }}" readonly wire:model="tkey">
                            <label for="tk-tkey">Key</label>
                        </div>
                    </div>
                    <div class="col-md right">
                        <div class="mb-2 right">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" id="tk-upd-created-at" value="{{ $ticket->ticket_created }}" readonly>
                                <label for="tk-upd-created-at">Create Date</label>
                            </div>
                        </div>
                    </div>
                </div>
                <h6 class="fg-forest">REPORTER</h6>
                {{-- {{ dd($users) }} --}}
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="hidden" name="caller" value="{{ $ticket->reporter }}">
                            <input type="text" class="form-control" id="tk-upd-username" value="{{ $ticket->user->username }}" readonly>
                            <label for="tk-username">Reporter</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="tk-upd-fullname" value="{{ $ticket->user->first_name . ' ' . $ticket->user->last_name }}" readonly>
                            <label for="tk-fullname">Name</label>
                        </div>
                    </div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="tk-upd-email" value="{{ $ticket->user->email }}" readonly>
                            <label for="tk-email">Email</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control" id="tk-upd-group" value="{{ $ticket->user->group->name }}" readonly>
                            <label for="tk-group">Group</label>
                        </div>
                    </div>
                </div>
                <h6 class="fg-forest">TICKET</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select name="priority" id="tk-upd-priority" wire:model="priority" class="form-select @error('priority') is-invalid @enderror">
                                <option value="urgent" {{ ( $ticket->priority == 'urgent' ) ? 'selected' : '' }}>Urgent</option>
                                <option value="important" {{ ( $ticket->priority == 'important' ) ? 'selected' : '' }}>Important</option>
                                <option value="task" {{ ( $ticket->priority == 'task' ) ? 'selected' : '' }}>Task</option>
                                <option value="request" {{ ( $ticket->priority == 'request' ) ? 'selected' : '' }}>Request</option>
                            </select>
                            @error('priority')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                            <label for="tk-priority">Priority</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-floating mb-3">
                            <select name="status" id="tk-upd-status" class="form-select @error('status') is-invalid @enderror" wire:model="status">
                                @if (old('status') == '' || old('status') == null)
                                    <option value="new" {{ ( $ticket->status == 'new' ) ? 'selected' : '' }}>New</option>
                                    <option value="in-progress" {{ ( $ticket->status == 'in-progress' ) ? 'selected' : '' }}>In Progress</option>
                                    <option value="on-hold" {{ ( $ticket->status == 'on-hold' ) ? 'selected' : '' }}>On Hold</option>
                                    <option value="resolved" {{ ( $ticket->status == 'resolved' ) ? 'selected' : '' }}>Resolved</option>
                                @else
                                    <option value="new" {{ ( old('status') == 'new' ) ? 'selected' : '' }}>New</option>
                                    <option value="in-progress" {{ ( old('status') == 'in-progress' ) ? 'selected' : '' }}>In Progress</option>
                                    <option value="on-hold" {{ ( old('status') == 'on-hold' ) ? 'selected' : '' }}>On Hold</option>
                                    <option value="resolved" {{ ( old('status') == 'resolved' ) ? 'selected' : '' }}>Resolved</option>
                                @endif
                            </select>
                            @error('status')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                            <label for="tk-status">Status</label>
                        </div>
                    </div>
                </div>
                @livewire('tickets-edit', [
                    'group'     =>  $ticket->group_id,
                    'assignee'  =>  $ticket->assignee,
                ])
                <h6 class="fg-forest">DETAILS</h6>
                <div class="row mb-3">
                    <div class="col">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                                placeholder="Title" value="{{ $ticket->title }}" maxlength="100" wire:model.debounce.1000ms="title"
                                {{ ( $access->group_id != $ticket->user->group_id ) ? 'readonly' : '' }}>
                            @error('title')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                            <label for="title">Title</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea name="description" id="tk-upd-description" cols="30" rows="20"
                                class="form-control @error('description') is-invalid @enderror" wire:model.debounce.1000ms="description"
                                placeholder="Type the ticket description here.." style="min-height: 147px;" maxlength="4000"
                                {{ ( $access->group_id != $ticket->user->group_id ) ? 'readonly' : '' }}>{!! $ticket->description !!}</textarea>
                            @error('description')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                            <label for="tk-description">Description</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3" hidden>
                    {{ $ticket }} <br>
                    {{ $errors }}
                </div>
            </form>
        </div>
        <div class="mb-2">
            @livewire('upload-attachment', ['tkey' => $ticket->tkey])
        </div>
        <div class="mb-3">
            @livewire('tickets-comments', ['tkey' => $ticket->tkey])
        </div>
    </div>
</div>
@endsection