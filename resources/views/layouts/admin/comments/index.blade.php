@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Quản lý bình luận</h4>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Bài viết</th>
                <th>Người bình luận</th>
                <th>Nội dung</th>
                <th>Ngày</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($comments as $comment)
                <tr>
                    <td>{{ $comment->id }}</td>
                    <td>
                        <a href="{{ route('blog.detail.show', $comment->blog->slug) }}" target="_blank">
                            {{ $comment->blog->slug }}
                        </a>
                    </td>
                    <td>{{ $comment->user->name ?? 'Khách' }}</td>
                    <td>{{ $comment->content }}</td>
                    <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('admin.comments.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Xóa bình luận này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Xóa</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Chưa có bình luận nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    <div class="mt-3">
        {{ $comments->links() }}
    </div>
</div>
@endsection
