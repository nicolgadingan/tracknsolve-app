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
    {{ $group }}
</div>