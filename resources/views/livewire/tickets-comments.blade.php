<div>
    <div class="d-flex align-items-center mb-2">
        <h6 class="fg-forest mb-0">
            COMMENTS
        </h6>
        <span class="badge bg-cheese fg-marine fs-sm ml-2">
            {{ count($comments) }}
        </span>
    </div>
    @if ($status != 'closed')
        <div class="mb-3">
            <form wire:submit.prevent="postComment">
                <div class="row g-3">
                    <div class="col-md">
                        <textarea name="comment" id="tk-comment" cols="30" rows="2" placeholder="Type your comment here..."
                            class="form-control @error('comment') is-invalid @enderror border-round" wire:model="comment"></textarea>
                        @error('comment')
                            <span class="invalid-feedback">
                                {{ $message }}
                            </span>                
                        @enderror
                    </div>
                    <div class="col-sm-auto align-self-end">
                        <button class="btn btn-primary" type="submit">Post</button>
                    </div>
                </div>
            </form>
        </div>
    @endif
    <ul class="list-group fs-sm">
        @if(count($comments) > 0)
            @foreach ($comments as $comment)
                <li class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between">
                        <a href="/users/{{ $comment->user->id }}" class="link-primary">
                            <strong>
                                {{ $comment->user->first_name . ' ' . $comment->user->last_name }}
                            </strong>
                        </a>
                        <span>{{ \Carbon\Carbon::create($comment->created_at)->diffForHumans() }}</span>
                    </div>
                    <pre class="fs-sm breakline">{{ $comment->comments }}</pre>
                </li>
            @endforeach
        @else
            <li class="list-group-item">
                <i>No comments found.</i>
            </li>
        @endif
    </ul>
</div>
