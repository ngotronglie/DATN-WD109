@foreach($comments as $comment)
    <div class="comment">
        <strong>{{ $comment->user->name }}</strong>
        <p>{{ $comment->content }}</p>
        <a href="#" class="reply-btn" data-id="{{ $comment->id }}">Trả lời</a>
        <form action="{{ route('comments.reply', $comment->id) }}" method="POST" class="reply-form mt-2 p-3 rounded shadow-sm" id="reply-form-{{ $comment->id }}" style="display: none; background: #f8f9fa;">
            @csrf
            <div class="d-flex align-items-start mb-2">
                <img src="{{ Auth::user()->avatar ?? asset('frontend/img/author/1.jpg') }}" alt="avatar" class="rounded-circle me-2" width="36" height="36">
                <textarea name="content" class="form-control" rows="2" placeholder="Nhập phản hồi của bạn..." required></textarea>
            </div>
            <div class="d-flex justify-content-end">
                <button type="button" class="btn btn-secondary btn-sm me-2 cancel-reply-btn" data-id="{{ $comment->id }}">Hủy</button>
                <button type="submit" class="btn btn-primary btn-sm">Gửi phản hồi</button>
            </div>
        </form>
        @if($comment->replies)
            <div class="replies" style="margin-left: 40px;">
                @include('components.client.comment_replies', ['comments' => $comment->replies])
            </div>
        @endif
    </div>
   
        <script>
            document.querySelectorAll('.cancel-reply-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        let form = document.getElementById('reply-form-' + this.dataset.id);
        form.style.display = 'none';
    });
});  </script>
@endforeach
