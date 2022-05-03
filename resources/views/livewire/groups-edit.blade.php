<form wire:submit.prevent="updateGroup" id="gr-edit-form-submit">
    <div class="d-flex justify-content-between p-3 align-items-center">
        <h5 class="modal-title" id="gr-edit-group-label">{{ $group['name'] }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <input type="hidden" id="gr-edit-group-id">
    <div class="pl-4 pr-4">
        @include('plugins.messages')
    </div>
    <div class="p-4 pt-0">
        <div class="form-floating mb-3">
            <input type="text" class="form-control @error('group_name') is-invalid @enderror" name="group_name" placeholder="Gorup name"
                id="gr-edit-name" wire:model.debounce.500ms="group_name" value="{{ $group_name }}">
            @error('group_name')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>
            @enderror
            <label for="gr-edit-name">Group Name</label>
        </div>
        <div class="form-floating">
            <select id="gr-new-manager" class="form-select @error('manager_id') is-invalid @enderror" wire:model="manager_id"
                aria-placeholder="Manager" {{ ( count($managers) == 0 ) ? 'disabled' : '' }}>
                @if (count($managers) > 0)
                    @foreach ($managers as $manager)
                        <option value="{{ $manager->id }}">
                            {{ ucwords($manager->first_name . ' ' . $manager->last_name) }}
                        </option>
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
        <button type="submit" class="btn btn-marine shadow-sm" {{ ( count($errors) > 0 ) ? 'disabled' : '' }}>
            Update
        </button>
    </div>
</form>


<script>
    $(document).ready(function() {
        $("body").on("click", ".gr-view-group", function() {
            var gval    =   $(this).data("value"),
                modal   =   $("#gr-edit-group-form");

            @this.group_id  =   gval;
            modal.modal("show");

            @this.reload();
        });
    });
</script>