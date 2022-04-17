@extends('layouts.app')

@section('page')
    New Ticket
@endsection

@section('content')
<div class="container-fluid">
    @include('plugins.previous')
    <div class="card card-body border-round border-forest-light pt-4">
        
        <h6 class="fg-forest">REPORTER</h6>
        <div class="row g-3">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="tk-username" value="{{ $reporter->username }}" readonly>
                    <label for="tk-username">Reporter</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="tk-fullname" value="{{ $reporter->first_name . ' ' . $reporter->last_name }}" readonly>
                    <label for="tk-fullname">Name</label>
                </div>
            </div>
        </div>
        <div class="row g-3 mb-2">
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="tk-email" value="{{ $reporter->email }}" readonly>
                    <label for="tk-email">Email</label>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="tk-group" value="{{ $reporter->group->name }}" readonly>
                    <label for="tk-group">Group</label>
                </div>
            </div>
        </div>
        <form action="/tickets" method="POST">
            @csrf
            <h6 class="fg-forest">TICKET</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select name="priority" id="tk-priority" class="form-select must-select @error('priority') is-invalid @enderror">
                            <option value="">Select Priority</option>
                            <option value="urgent" {{ (old('priority') == 'urgent') ? 'selected' : '' }}>Urgent</option>
                            <option value="important" {{ (old('priority') == 'important') ? 'selected' : '' }}>Important</option>
                            <option value="task" {{ (old('priority') == 'task') ? 'selected' : '' }}>Task</option>
                            <option value="request" {{ (old('priority') == 'request') ? 'selected' : '' }}>Request</option>
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
                        <select name="status" id="tk-status" class="form-select">
                            <option value="new" selected>New</option>
                            {{-- <option value="in-progress" {{ (old('priority') == 'in-progress') ? 'selected' : '' }}>In-Progress</option>
                            <option value="on-hold" {{ (old('priority') == 'on-hold') ? 'selected' : '' }}>On Hold</option>
                            <option value="resolved" {{ (old('priority') == 'resolved') ? 'selected' : '' }}>Resolved</option> --}}
                        </select>
                        <label for="tk-status">Status</label>
                    </div>
                </div>
            </div>
            @livewire('tickets-get-users', ['groups' => $groups], key($groups->id))
            <h6 class="fg-forest">DETAILS</h6>
            <div class="row mb-2">
                <div class="col">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title" value="{{ old('title') }}">
                        @error('title')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                        <label for="title">Title</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="description" id="tk-description" cols="30" rows="20" class="form-control @error('description') is-invalid @enderror"
                            placeholder="Type the ticket description here.." style="min-height: 147px;">{!! old('description') !!}</textarea>
                        @error('description')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                        <label for="tk-description">Description</label>
                    </div>
                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tk-attachment">Attachment</label>
                        <input type="file" class="form-control @error('attachment') is-invalid @enderror" id="tk-attachment" name="attachment" placeholder="Attachment">
                        @error('attachment')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                        <small class="text-muted">File should only have max size of 2Mb.</small>
                    </div>
                </div>
            </div>
            <div class="mb-2 right">
                <button type="submit" class="btn btn-marine btn-lg">Submit</button>
            </div>
        </form>
    </div>
    <script>
        $(document).ready(function() {
            $("body").on("change", "select.must-select", function() {
                var thisObj = $(this);
                if (thisObj.children("option:selected").val() == "") {
                    thisObj.addClass("is-invalid");
                } else {
                    thisObj.removeClass("is-invalid");
                }
            });

            $("body").on("change", "#tk-assignmentx", function() {
                var thisObj = $(this);
                var inpAsgn = $("#tk-assignee")
                if (thisObj.children("option:selected").val() == "") {
                    inpAsgn.empty();
                } else {
                    thisObj.removeClass("is-invalid");
                }
            });
        });
    </script>
</div>
@endsection