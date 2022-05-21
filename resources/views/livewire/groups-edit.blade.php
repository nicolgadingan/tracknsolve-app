<div class="card card-body borderless border-round shadow-sm pt-4">
    @php
        $role   =   auth()->user()->role;
        $extra  =   $isEditable ? '' : 'disabled';
    @endphp
    <form wire:submit.prevent="saveUpdate">
        @csrf
        @method('PUT')
        <div class="d-flex justify-content-between mb-4">
            @includeIf('plugins.previous', ['path' => '/groups'])
            @if (auth()->user()->role == "admin")
                <button type="submit" class="btn btn-marine btn-lg shadow-sm mb-2" {{ ( count($errors) > 0 ) || !$hasUpdate ? 'disabled' : '' }}>
                    Update
                </button>
            @endif
        </div>
        @include('plugins.messages')
        <h6 class="fg-forest">GROUP DETAILS</h6>
        <div class="mb-4">
            <div class="form-floating mb-3">
                <input type="text" class="form-control @error('group_name') is-invalid @enderror" name="group_name"
                    placeholder="Gorup name" id="gr-edit-name" value="{{ $group_name }}" {{ $extra }} wire:model="group_name">
                @error('group_name')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
                <label for="gr-edit-name">Group Name</label>
            </div>
            <div x-data="{ charrem: 255 }" class="mb-3" x-init="chars = $refs.descn.value.length; charrem = $refs.descn.maxLength - chars">
                <div class="form-floating">
                    <textarea id="gr-edit-descn" name="description" class="form-control @error('description') is-invalid @enderror"
                        style="height: 92px;" {{ $extra }} maxlength="255" x-ref="descn" wire:model.debounce.500ms="description"
                        x-on:keyup="chars = $refs.descn.value.length; charrem = $refs.descn.maxLength - chars">{{ $description }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>
                    @enderror
                    <label for="gr-edit-descn">Description</label>
                </div>
                <div class="pt-1 ml-2">
                    <span x-html="charrem"></span> remaining characters
                </div>
            </div>
            <div class="form-floating">
                <select id="gr-new-manager" name="manager_id" class="form-select @error('manager_id') is-invalid @enderror"
                    {{ ( count($managers) == 0 ) ? 'disabled' : '' }} {{ $extra }} wire:model="manager_id">
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
                @error('manager_id')
                    <span class="invalid-feedback">
                        {{ $message }}
                    </span>
                @enderror
                <label for="gr-new-manager">Manager</label>
            </div>
        </div>
        </form>
        <h6 class="fg-forest pt-2" hidden>MEMBERS</h6>
        <ul class="list-group" hidden>
            @if (count($members) > 0)
                @foreach ($members as $member)
                    <li class="list-group-item list-group-item-action">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <strong>{{ ucwords($member->first_name . ' ' . $member->last_name) }}</strong><br>
                                <span>{{ $member }}</span>
                            </div>
                            <i class="bi-check-circle-fill fg-{{ ($member->status == 'A') ? 'success' : 'secondary' }}"></i>
                        </div>
                    </li>
                @endforeach
            @else
                <li class="list-group-item list-group-item-action">
                    No members.
                </li>
            @endif
        </ul>
    </div>
</div>