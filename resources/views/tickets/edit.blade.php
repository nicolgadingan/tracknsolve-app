@extends('layouts.app')

@section('page')
    New Ticket
@endsection

@section('content')
<div class="container" id="tk-create-box">
    @include('plugins.previous')
    <div class="card card-body border-round border-forest-light pt-4">
        @include('plugins.messages')
        <form action="/tickets" method="POST" id="tk-create-form">
            @csrf
            @method('PUT')
            <h6 class="fg-forest">STATUS</h6>
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="text" name="tkey" class="form-control" id="tk-tkey" value="{{ $tkey }}" readonly>
                        <label for="tk-tkey">Key</label>
                    </div>
                </div>
                <div class="col-md-6 right">
                    <div class="mb-2 right">
                        <button type="button" class="btn btn-marine btn-lg" id="tk-create-submit">
                            Submit
                        </button>
                    </div>
                </div>
            </div>
            <h6 class="fg-forest">REPORTER</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <input type="hidden" name="reporter" value="{{ $reporter->id }}">
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
            <h6 class="fg-forest">TICKET</h6>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-floating mb-3">
                        <select name="priority" id="tk-priority" class="form-select must-select @error('priority') is-invalid @enderror">
                            <option value="">Select Priority</option>
                            <option value="urgent" {{ ($ticket->priority == 'urgent') ? 'selected' : '' }}>Urgent</option>
                            <option value="important" {{ ($ticket->priority == 'important') ? 'selected' : '' }}>Important</option>
                            <option value="task" {{ ($ticket->priority == 'task') ? 'selected' : '' }}>Task</option>
                            <option value="request" {{ ($ticket->priority == 'request') ? 'selected' : '' }}>Request</option>
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
                            <option value="new" {{ ($ticket->status == 'new') ? 'selected' : '' }}>New</option>
                            <option value="in-progress" {{ ($ticket->status == 'in-progress') ? 'selected' : '' }}>In-Progress</option>
                            <option value="on-hold" {{ ($ticket->status == 'on-hold') ? 'selected' : '' }}>On Hold</option>
                            <option value="resolved" {{ ($ticket->status == 'resolved') ? 'selected' : '' }}>Resolved</option>
                        </select>
                        <label for="tk-status">Status</label>
                    </div>
                </div>
            </div>
            @livewire('tickets-get-users', ['assignment' => $ticket->group->name])
            <h6 class="fg-forest">DETAILS</h6>
            <div class="row mb-2">
                <div class="col">
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" placeholder="Title" value="{{ old('title') }}" maxlength="100">
                        @error('title')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                        <label for="title">Title</label>
                    </div>
                    <div class="form-floating mb-3">
                        <textarea name="description" id="tk-description" cols="30" rows="20" class="form-control @error('description') is-invalid @enderror"
                            placeholder="Type the ticket description here.." style="min-height: 147px;" maxlength="4000">{!! old('description') !!}</textarea>
                        @error('description')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>
                        @enderror
                        <label for="tk-description">Description</label>
                    </div>
                </div>
            </div>
        </form>
        <div class="mb-2">
            @livewire('upload-attachment', ['tkey' => $tkey])
        </div>
        <div class="mb-3">
            @livewire('tickets-comments', ['tkey' => $tkey])
        </div>
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

            $("body").on("input", "#tk-assignment", function() {
                var thisObj = $(this);
                var inpAsgn = $("#tk-assignee");
                if (thisObj.val() == "") {
                    inpAsgn.val("");
                }
            });

            $("body").on("click", "#tk-create-submit", function() {
                $("#tk-create-form").submit();
            });

            // Delete attachment
            $("body").on("click", ".tk-del-att", function() {
                var attid   =   $(this).data('value'),
                    tarBtn  =   $("#tk-delatt-btn"),
                    tarInp  =   $("#tk-delatt-id");

                // $("#tk-delatt-id").val(attid, function() {
                //     tarBtn.trigger("click");
                // });

                tarInp.val(attid, function() {
                    tarBtn.trigger("click");
                    console.log("Trigger completed.");
                });
            });
        });
    </script>
</div>
@endsection