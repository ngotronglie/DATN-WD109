@extends('index.admindashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h3 class="card-title mb-0">Chi tiết tin nhắn liên hệ</h3>
                        </div>
                        <div class="col-6 text-end">
                            <a href="{{ route('admin.contact.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-arrow-left"></i> Quay lại
                            </a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Họ và tên:</label>
                                <p class="form-control-plaintext">{{ $contact->con_name }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Email:</label>
                                <p class="form-control-plaintext">{{ $contact->con_email }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Số điện thoại:</label>
                                <p class="form-control-plaintext">{{ $contact->con_phone }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Tiêu đề:</label>
                                <p class="form-control-plaintext">{{ $contact->con_subject }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Người gửi:</label>
                                <p class="form-control-plaintext">
                                    @if($contact->user_id)
                                        <span class="badge bg-primary">User ID: {{ $contact->user_id }}</span>
                                        @if($contact->user)
                                            ({{ $contact->user->name }})
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Khách</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Trạng thái:</label>
                                <p class="form-control-plaintext">
                                    <span class="badge bg-{{ $contact->status == 'pending' ? 'warning' : ($contact->status == 'read' ? 'info' : 'success') }}">
                                        {{ $contact->status_text }}
                                    </span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label class="fw-bold">Nội dung tin nhắn:</label>
                                <div class="form-control-plaintext" style="min-height: 200px; background-color: #f8f9fa; padding: 20px; border-radius: 8px; white-space: pre-wrap;">
                                    {{ $contact->con_message }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Ngày gửi:</label>
                                <p class="form-control-plaintext">{{ $contact->created_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="fw-bold">Cập nhật lần cuối:</label>
                                <p class="form-control-plaintext">{{ $contact->updated_at->format('d/m/Y H:i:s') }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="btn-group" role="group">
                                @if($contact->status !== 'read')
                                    <button type="button" class="btn btn-info" onclick="markAsRead({{ $contact->id }})">
                                        <i class="fas fa-eye"></i> Đánh dấu đã đọc
                                    </button>
                                @endif
                                @if($contact->status !== 'replied')
                                    <button type="button" class="btn btn-success" onclick="markAsReplied({{ $contact->id }})">
                                        <i class="fas fa-reply"></i> Đánh dấu đã phản hồi
                                    </button>
                                @endif
                                <button type="button" class="btn btn-danger" onclick="deleteContact({{ $contact->id }})">
                                    <i class="fas fa-trash"></i> Xóa
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-group {
        margin-bottom: 1rem;
    }
    .form-group label {
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    .form-control-plaintext {
        border: 1px solid #dee2e6;
        padding: 0.5rem;
        border-radius: 0.375rem;
        background-color: #fff;
    }
</style>
@endpush

@push('scripts')
<script>
function markAsRead(contactId) {
    fetch(`/admin/contacts/${contactId}/mark-read`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

function markAsReplied(contactId) {
    fetch(`/admin/contacts/${contactId}/mark-replied`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra');
    });
}

function deleteContact(contactId) {
    if (confirm('Bạn có chắc chắn muốn xóa tin nhắn này?')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/contacts/${contactId}`;
        
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        const methodField = document.createElement('input');
        methodField.type = 'hidden';
        methodField.name = '_method';
        methodField.value = 'DELETE';
        
        form.appendChild(csrfToken);
        form.appendChild(methodField);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
@endsection 