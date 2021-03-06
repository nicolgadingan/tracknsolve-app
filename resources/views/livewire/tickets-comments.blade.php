<div>
    <div class="d-flex align-items-center mb-2">
        <h6 class="fg-forest mb-0">
            COMMENTS
        </h6>
        <span class="badge bg-darker-shade fg-forest fs-sm ml-2">
            {{ count($comments) }}
        </span>
    </div>
    @if ($status != 'closed')
        <div class="mb-3">
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
                    <div class="col-sm-auto align-self-end">
                        <button class="btn btn-main" type="submit">Post</button>
                    </div>
                </div>
            </form>
        </div>
    @endif
    <ul class="ts-list">
        @if(count($comments) > 0)
            @foreach ($comments as $comment)
                <li class="ts-list-item">
                    <div class="d-flex justify-content-between mb-1">
                        <a href="/users/{{ $comment->user->id }}" class="link-primary">
                            <strong>
                                {{ $comment->user->first_name . ' ' . $comment->user->last_name }}
                            </strong>
                        </a>
                        <span>{{ \Carbon\Carbon::create($comment->created_at)->diffForHumans() }}</span>
                    </div>
                    <span class="fs-sm breakline">{{ $comment->comments }}</span>
                </li>
            @endforeach
        @else
            <li class="ts-list-item">
                <i>No comments found.</i>
            </li>
        @endif
    </ul>
</div>
