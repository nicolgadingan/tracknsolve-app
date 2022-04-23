<div>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                <label for="tk-attachment">Attachment</label>
                <form wire:submit.prevent="save">
                    <div class="row g-3">
                        <div class="col-sm">
                            <input type="hidden" wire:model="tkey" value="{{ $tkey }}">
                            <input type="file" class="form-control @error('attachment') is-invalid @enderror" id="tk-attachment"
                                aria-describedby="attachment-button" aria-label="Upload" name="attachment" wire:model="attachment">
                            @error('attachment')
                                <span class="invalid-feedback">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-outline-secondary" type="submit" id="attachment-button">Upload</button>
                        </div>
                    </div>
                    <div class="p-2" wire:loading wire:target="attachment">Checking...</div>
                </form>
            </div>
            <div class="mb-3">
                @if (count($files) > 0)
                    <input type="hidden" wire:model="xfile" id="tk-delatt-id">
                    <button class="d-none" wire:click="delatt" id="tk-delatt-btn"></button>
                    <ul class="list-group">
                        @foreach ($files as $file)
                            <li class="list-group-item d-flex justify-content-between">
                                <a href="{{ asset('storage/' . $file->att_path) }}" class="link-primary" download="{{ $file->att_path }}">
                                    {{ Arr::last(explode('/', $file->att_path)) }}
                                </a>
                                {{-- <a href="#del-att" class="link-danger tk-del-att" data-value="{{ $file->id }}">
                                    <i class="bi bi-trash-fill"></i>
                                </a> --}}
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