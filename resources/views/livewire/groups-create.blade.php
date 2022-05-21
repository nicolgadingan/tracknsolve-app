<form wire:submit.prevent="saveGroup">
    <div class="modal-header">
        <h5 class="modal-title" id="gr-new-group-label">New Group</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body" x-data="{ remain: 255 }" x-init="remain = $refs.descn.maxLength; chars = $refs.descn.value.length">
        @include('plugins.messages')
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
            <textarea id="gr-new-description" cols="30" rows="20" class="form-control" maxlength="255" wire:model="descn"
                x-ref="descn" x-on:keyup="chars = $refs.descn.value.length; remain = $refs.descn.maxLength - chars"></textarea>
            <label for="gr-new-description">Description</label>
        </div>
        <div class="mb-3 pt-1 pl-2">
            <span x-html="remain"></span> characters left
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
    <div class="modal-footer">
        <button type="submit" class="btn btn-marine btn-lg shadow-sm" {{ ( count($errors) > 0 ) ? 'disabled' : '' }}>Submit</button>
    </div>
    <div hidden>{{ $errors }}</div>
</form>