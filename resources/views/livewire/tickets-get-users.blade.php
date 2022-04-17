<div class="row g-3 mb-2">
    <div class="col-md-6">
        <div class="form-floating mb-3">
            <select name="assignment_group" id="tk-assignment" class="form-select must-select @error('assignment_group') is-invalid @enderror" aria-placeholder="Group" wire:model="query">
                {{-- <option value="">Select Group</option> --}}
                @foreach ($groups as $group)
                    <option value="{{ $group->id }}" {{ old('assignment_group') == $group->id ? 'selected' : '' }}>{{ $group->name }}</option>
                @endforeach
            </select>
            @error('assignment_group')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
            <label for="tk-assignment" class="form-label">Assignment Group</label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-floating mb-3">
            {{-- <input type="text" class="form-control" id="assignee" name="assignee" placeholder="Assignee"> --}}
            <select name="assignee" id="tk-assignee" class="form-select" aria-placeholder="Assignee">
                @if (count($users) > 0)
                    <option value="">Select Assignee</option>
                    @foreach ($users as $user)
                        <option value="{{ $user->id }}">{{ $user->first_name . ' ' . $user->last_name }}</option>
                    @endforeach
                @else
                    <option value="">No available assignee</option>
                @endif
            </select>
            <label for="tk-assignee">Assignee</label>
        </div>
    </div>
</div>