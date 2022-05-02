<form wire:submit.prevent="createGroup">
    <div class="p-4 pt-0">
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" placeholder="Gorup name"
                id="gr-new-name" wire:model.debounce.500ms="name">
            @error('name')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
            <label for="gr-new-name">Group Name</label>
        </div>
        <div class="form-floating">
            <select id="gr-new-manager" class="form-select @error('manager') is-invalid @enderror" wire:model="manager"
                aria-placeholder="Manager" {{ ( count($managers) == 0 ) ? 'disabled' : '' }}>
                @if (count($managers) > 0)
                    <option value="">Select Manager</option>
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}">{{ ucwords($manager->first_name . ' ' . $manager->last_name) }}</option>
                    @endforeach
                @else
                    <option value="">No available manager</option>
                @endif
            </select>
            @error('manager')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
            <label for="gr-new-manager">Manager</label>
        </div>
    </div>
    <div class="mb-3 p-4 pt-0 right">
        <button type="submit" class="btn btn-marine shadow-sm" {{ ( count($errors) > 0 ) ? 'disabled' : '' }}>Submit</button>
    </div>
</form>