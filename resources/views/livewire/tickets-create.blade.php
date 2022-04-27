<form wire:submit.prevent="submitTicket">
    @include('plugins.messages')
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
                <button type="submit" class="btn btn-marine btn-lg" id="tk-create-submit">
                    Submit
                </button>
            </div>
        </div>
    </div>
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
    <h6 class="fg-forest">TICKET</h6>
    <div class="row g-3">
        <div class="col-md-6">
            <div class="form-floating mb-3">
                <select name="priority" id="tk-priority" wire:model="priority" class="form-select @error('priority') is-invalid @enderror">
                    <option value="">Select Priority</option>
                    <option value="urgent">Urgent</option>
                    <option value="important">Important</option>
                    <option value="task">Task</option>
                    <option value="request">Request</option>
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
                <select name="status" id="tk-status" class="form-select"}} disabled>
                    <option value="new">New</option>
                </select>
                <label for="tk-status">Status</label>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-2">
        <div class="col-md-6">
            <div class="form-floating mb-3">
                <select name="group" id="tk-assignment" class="form-select @error('group') is-invalid @enderror" placeholder="Group name" wire:model="group">
                    @if (count($groups) > 0)
                        <option value="999">Select Assignment Group</option>
                        @foreach ($groups as $grp)
                            <option value="{{ $grp->id }}">{{ $grp->name }}</option>
                        @endforeach
                    @endif
                </select>
                @error('group')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
                <label for="tk-assignment" class="form-label">Assignment Group</label>
            </div>
        </div>
        <div class="col-md-6" style="position: relative;">
            <div class="search">
                <div class="form-floating mb-1">
                    <select name="assignee" wire:model="assignee" id="tk-assignee" class="form-select @error('assignee') is-invalid @enderror">
                        @if (count($users) > 0)
                            <option value="">Select Assignee (optional)</option>
                            @foreach ($users as $udata)
                                <option value="{{ $udata->id }}">{{ $udata->first_name . ' ' . $udata->last_name }}</option>
                            @endforeach
                        @endif
                    </select>
                    @error('assignee')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <label for="tk-assignee">Assignee</label>
                </div>
            </div>
        </div>
    </div>
    <h6 class="fg-forest">DETAILS</h6>
    <div class="row mb-2">
        <div class="col">
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title"
                    placeholder="Title" value="{{ old('title') }}" maxlength="100" wire:model.debounce.1000ms="title">
                @error('title')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
                <label for="title">Title</label>
            </div>
            <div class="form-floating mb-3">
                <textarea name="description" id="tk-description" cols="30" rows="20"
                    class="form-control @error('description') is-invalid @enderror" wire:model.debounce.1000ms="description"
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