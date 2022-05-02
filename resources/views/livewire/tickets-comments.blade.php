<div>
    <h6 class="fg-forest">COMMENTS</h6>
    <div class="mb-2">
        <form wire:submit.prevent="postComment">
            <div class="row g-3">
                <div class="col-md">
                    <textarea name="comment" id="tk-comment" cols="30" rows="2" placeholder="Type your comment here..."
                        class="form-control @error('comment') is-invalid @enderror" wire:model="comment"></textarea>
                    @error('comment')
                        <span class="invalid-feedback">
                            {{ $message }}
                        </span>                
                    @enderror
                </div>
                <div class="col-sm-auto">
                    <button class="btn btn-primary" type="submit">Post</button>
                </div>
            </div>
        </form>
    </div>
    <hr>
    <ul class="list-group list-group-flush fs-sm">
        @if(count($comments) > 0)
            @foreach ($comments as $comment)
                <li class="list-group-item list-group-item-action">
                    <div class="d-flex justify-content-between">
                        <a href="/users/{{ $comment->user->id }}" class="link-dark">
                            <strong>
                                {{ $comment->user->first_name . ' ' . $comment->user->last_name }}
                            </strong>
                        </a>
                        <span>{{ \Carbon\Carbon::create($comment->created_at)->diffForHumans() }}</span>
                    </div>
                    <pre class="fs-sm">{{ $comment->comments }}</pre>
                </li>
            @endforeach
        @else
            <li class="list-group-item">
                <i>No comments found.</i>
            </li>
        @endif
    </ul>
</div>
