<div class="row g-3 mb-3">
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <select name="group" id="tk-upd-assignment" class="form-select @error('group') is-invalid @enderror" placeholder="Group name" wire:model="group">
                @if (count($groups) > 0)
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
        <div class="form-floating mb-1">
            <select name="assignee" wire:model="assignee" id="tk-upd-assignee" class="form-select @error('assignee') is-invalid @enderror">
                @if (count($users) > 0)
                    <option value="">Select Assignee (optional)</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->user_id }}">{{ $user->first_name . ' ' . $user->last_name }}</option>
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
        <div class="mb-3" hidden>
            {{ 'Group: ' . $group }}
            <br>
            {{ 'Assignee: ' . $assignee }}
        </div>
    </div>
</div>