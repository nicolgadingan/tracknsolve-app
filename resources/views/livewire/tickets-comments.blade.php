<div>
    <h6 class="fg-forest">COMMENTS</h6>
    <div class="mb-2">
        <form wire:submit.prevent="post">
            <textarea name="comment" id="tk-comment" cols="30" rows="2" placeholder="Type your comment here..."
                class="form-control @error('comment') is-invalid @enderror" wire:model="comment"></textarea>
            @error('comment')
                <span class="invalid-feedback">
                    {{ $message }}
                </span>                
            @enderror
            <div class="right mt-2">
                <button class="btn btn-primary" type="submit">Post Comment</button>
            </div>
        </form>
    </div>
    <hr>
    <ul class="list-group">
        @if(count($comments) > 0)
            @foreach ($comments as $comment)
                <li class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between">
                        <strong>{{ $comment->user->first_name . ' ' . $comment->user->last_name }}</strong>
                        <span>{{ \Carbon\Carbon::create($comment->created_at)->diffForHumans() }}</span>
                    </div>
                    <span>{{ $comment->comments }}</span>
                </li>
            @endforeach
        @else
            <li class="list-group-item">
                <i>No comments found.</i>
            </li>
        @endif
    </ul>
</div>
