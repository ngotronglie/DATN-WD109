@foreach($comments as $comment)
    <div class="comment">
        <strong>{{ $comment->user->name }}</strong>
        <p>{{ $comment->content }}</p>
        <a href="#" class="reply-btn" data-id="{{ $comment->id }}">Trả lời</a>
        <div class="reply-form" id="reply-form-{{ $comment->id }}" style="display:none;">
            <form method="POST" action="{{ route('comments.reply', $comment->id) }}">
                @csrf
                <textarea name="content"></textarea>
                <button type="submit">Gửi</button>
            </form>
        </div>
        @if($comment->replies)
            <div class="replies" style="margin-left: 40px;">
                @include('components.comment_replies', ['comments' => $comment->replies])
            </div>
        @endif
    </div>
@endforeach
