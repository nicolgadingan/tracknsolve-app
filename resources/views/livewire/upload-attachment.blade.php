<div>
    <div class="d-flex align-items-center mb-2">
        <h6 class="fg-forest mb-0">
            ATTACHMENTS
        </h6>
        <span class="badge bg-cheese fg-marine fs-sm ml-2">
            {{ count($files) }}
        </span>
    </div>
    <div class="row mb-3">
        <div class="col-md-6">
            @if ($status != 'closed')
                <div class="mb-3">
                    <form wire:submit.prevent="save">
                        <div class="row g-3">
                            <div class="col-sm">
                                <input type="hidden" wire:model="tkey" value="{{ $tkey }}">
                                <div class="has-icon has-icon-end">
                                    <input type="file" class="form-control has-icon-form @error('attachment') is-invalid @enderror" id="tk-attachment"
                                        aria-describedby="attachment-button" aria-label="Upload" name="attachment" wire:model="attachment">
                                    @error('attachment')
                                        <span class="invalid-feedback">
                                            {{ $message }}
                                        </span>
                                    @enderror
                                    <div wire:loading wire:target="attachment" class="spinner-grow spinner-grow-sm has-icon-this" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-auto">
                                <button class="btn btn-primary" type="submit" id="attachment-button" {{ ($attachment == '') ? 'disabled' : '' }}>
                                    Upload
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
            <div class="mb-3">
                @if (count($files) > 0)
                    <input type="hidden" wire:model="xfile" id="tk-delatt-id">
                    <button class="d-none" wire:click="delatt" id="tk-delatt-btn" ></button>
                    <ul class="list-group">
                        @foreach ($files as $file)
                            <li class="list-group-item d-flex justify-content-between">
                                <a href="{{ asset('storage/' . $file->att_path) }}" class="link-primary" download="{{ $file->att_path }}">
                                    {{ Arr::last(explode('/', $file->att_path)) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                @else
                    No attachments
                @endif
            </div>
        </div>
    </div>
</div>