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
        </div>
    </div>
</div>
