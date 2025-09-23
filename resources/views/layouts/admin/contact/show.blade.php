@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-message-circle me-2"></i>
                    Chi tiết liên hệ #{{ $contact->id }}
                </h2>
            </div>
            <div class="col-auto ms-auto">
                <a href="{{ route('admin.contacts.index') }}" class="btn btn-secondary">← Quay lại danh sách</a>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div><strong>Họ và tên:</strong> {{ $contact->con_name }}</div>
                            <div><strong>Email:</strong> {{ $contact->con_email }}</div>
                            <div><strong>Điện thoại:</strong> {{ $contact->con_phone ?: '-' }}</div>
                        </div>
                        <div class="col-md-6">
                            <div><strong>Tiêu đề:</strong> {{ $contact->con_subject }}</div>
                            <div><strong>Ngày gửi:</strong> {{ $contact->created_at?->format('d/m/Y H:i') }}</div>
                            <div><strong>Trạng thái:</strong>
                                <span class="badge {{ $contact->status === 'replied' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $contact->status === 'replied' ? 'Đã phản hồi' : 'Chờ xử lý' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <strong>Nội dung:</strong>
                        <div class="form-control-plaintext border rounded p-3" style="white-space: pre-wrap;">
                            {{ $contact->con_message }}
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        @if($contact->status !== 'replied')
                        <form action="{{ route('admin.contacts.markReplied', $contact) }}" method="POST" onsubmit="return confirm('Đánh dấu đã phản hồi?')">
                            @csrf
                            <button class="btn btn-success">
                                <i class="ti ti-mail me-1"></i> Đánh dấu đã phản hồi
                            </button>
                        </form>
                        @endif
                        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST" onsubmit="return confirm('Xóa liên hệ này?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger">
                                <i class="ti ti-trash me-1"></i> Xóa
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
