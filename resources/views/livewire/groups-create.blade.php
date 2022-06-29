<form wire:submit.prevent="saveGroup">
    <div class="ts-card" x-data="{ remain: 255 }">
        <div class="d-flex justify-content-between mb-4">
            @includeIf('plugins.previous', ['path' => '/groups'])
            <button type="submit" class="btn btn-main btn-lg shadow-sm" {{ ( count($errors) > 0 ) ? 'disabled' : '' }}>
                Submit
            </button>
        </div>
        @include('plugins.messages')
        <h6 class="fg-forest">GROUP DETAILS</h6>
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
        <div class="form-floating mb-3">
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
</form>