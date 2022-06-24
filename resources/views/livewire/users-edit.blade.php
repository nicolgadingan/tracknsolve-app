@php
    $urole  =   auth()->user()->role;
    $uid    =   auth()->user()->id;
@endphp
<form wire:submit.prevent="updateUser">
    @csrf
    <div class="mb-4 d-flex justify-content-between">
        @include('plugins.previous', ['path'    =>  '/users'])
        <div style="min-height: 45px;">
            @if ($urole == "admin" && $editable == "Y")
                <button type="submit" class="btn btn-main btn-lg shadow" {{ ( count($errors) > 0 || $isFresh == true ) ? 'disabled' : '' }}>
                    Update
                </button>
            @endif
        </div>
    </div>
    @include('plugins.messages')
    <h6 class="fg-forest">ORGANIZATION</h6>
    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="form-floating">
                <select name="group" id="us-group" class="form-select" wire:model="group_id"
                    {{ ( $editable == "N" ) ? 'disabled' : '' }}>
                    @if (count($groups) > 0)
                        @foreach ($groups as $group)
                            <option value="{{ $group->id }}">
                                {{ $group->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="">No available group</option>
                    @endif
                </select>
                <label for="us-group">Group</label>
            </div>
        </div>
        <div class="col-md-6">
            <div class="col-md">
                <div class="form-floating">
                    <select name="role" id="us-edit-role" class="form-select" wire:model="role"
                        {{ ( $editable == "N" ) ? 'disabled' : '' }}>
                        <option value="user" {{ $role == 'user' ? 'selected' : '' }}>User</option>
                        <option value="manager" {{ $role == 'manager' ? 'selected' : '' }}>Manager</option>
                        <option value="admin" {{ $role == 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                    <label for="us-edit-role">Role</label>
                </div>
            </div>
        </div>
    </div>
    <h6 class="fg-forest">USER INFORMATION</h6>
    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="form-floating">
                <input type="text" class="form-control" name="username" id="us-edit-username"
                    maxlength="20" placeholder="Username" value="{{ $username }}" disabled>
                <label for="us-edit-username">Username</label>
                <i class="bi bi-info-circle-fill info-icon hover" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Username is an identifier and cannot be modified.">
                </i>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-floating">
                @php
                    $status_text    =   '';
                    switch ($user->status) {
                        case 'A':
                            $status_text    =   'Active';
                            break;
                        case 'I':
                            $status_text    =   'Inactive';
                            break;
                        case 'X':
                            $status_text    =   'Archived';
                            break;
                        default:
                            $stutus_text    =   'Unknown';
                            break;

                    }
                @endphp
                <input type="text" class="form-control" name="status" id="us-edit-status"
                    maxlength="20" placeholder="Username" value="{{ $status_text }}" disabled>
                <label for="us-edit-status">Username</label>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-3">
        <div class="col-md">
            <div class="form-floating">
                <input type="text" class="form-control @error('first_name') is-invalid @enderror" name="first_name"
                    id="us-edit-first-name" maxlength="50" placeholder="First name" value="{{ $first_name }}" wire:model="first_name"
                    {{ ( $editable == "N" ) ? 'disabled' : '' }}>
                @error('first_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <label for="us-edit-first-name">First Name</label>
            </div>
        </div>
        <div class="col-md">
            <div class="form-floating">
                <input type="text" class="form-control @error('last_name') is-invalid @enderror" name="last_name"
                    id="us-edit-last-name" maxlength="50" placeholder="Last name" value="{{ $last_name }}" wire:model="last_name"
                    {{ ( $editable == "N" ) ? 'disabled' : '' }}>
                @error('last_name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <label for="us-edit-first-name">Last Name</label>
            </div>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md">
            <div class="form-floating">
                <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" id="us-edit-email"
                    maxlength="50" placeholder="Email" value="{{ $email }}" disabled>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
                <label for="us-edit-email">Email</label>
                <i class="bi bi-info-circle-fill info-icon hover" data-bs-toggle="tooltip" data-bs-placement="bottom"
                    title="Email is an identifier and cannot be modified.">
                </i>
            </div>
        </div>
        <div class="col-md">
            <div class="form-floating">
                <input type="text" class="form-control" name="contact_no" id="us-edit-contact-no" maxlength="20" placeholder="Contact No"
                    value="{{ $contact_no }}" wire:model="contact_no" {{ ( $editable == "N" ) ? 'disabled' : '' }}>
                <label for="us-edit-contact-no">Contact Number</label>
            </div>
        </div>
    </div>
</form>