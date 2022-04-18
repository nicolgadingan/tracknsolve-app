<div class="row g-3 mb-2">
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('group') is-invalid @enderror" id="tk-assignment"
                list="tk-group-list" name="group" wire:model="group" placeholder="Group name">
            <datalist id="tk-group-list">
                @foreach ($groups as $grp)
                    <option value="{{ $grp->name }}" {{ old('group') == $grp->name ? 'selected' : '' }}>
                @endforeach
            </datalist>
            @error('group')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
            <label for="tk-assignment" class="form-label">Assignment Group</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('assignee') is-invalid @enderror" wire:model="assignee"
                name="assignee" id="tk-assignee" list="tk-assignee-list">
            <datalist id="tk-assignee-list">
                @foreach ($users as $user)
                    <option value="{{ $user->username }}">{{ $user->fullname }}</option>
                @endforeach
            </datalist>
            @error('assignee')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
            <label for="tk-assignee">Assignee</label>
        </div>
    </div>
</div>