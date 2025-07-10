@extends('layouts.admin.index')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="page-header d-print-none">
        <div class="row align-items-center">
            <div class="col">
                <h2 class="page-title">
                    <i class="ti ti-message-circle me-2"></i>
                    Quản lý Liên hệ
                </h2>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Danh sách tin nhắn liên hệ</h3>
                </div>
                <div class="table-responsive">
                    <table class="table table-vcenter card-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Họ và tên</th>
                                <th>Email</th>
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th>Ngày gửi</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($contacts as $contact)
                                <tr data-contact-id="{{ $contact->id }}" 
                                    data-name="{{ $contact->con_name }}"
                                    data-email="{{ $contact->con_email }}"
                                    data-phone="{{ $contact->con_phone }}"
                                    data-subject="{{ $contact->con_subject }}"
                                    data-message="{{ $contact->con_message }}"
                                    data-date="{{ $contact->created_at->format('d/m/Y H:i') }}">
                                    <td><span class="badge bg-primary">#{{ $contact->id }}</span></td>
                                    <td>{{ $contact->con_name }}</td>
                                    <td>{{ $contact->con_email }}</td>
                                    <td>{{ $contact->con_subject }}</td>
                                    <td>
                                        {{ Str::limit($contact->con_message, 50) }}
                                        <a href="#" onclick="showMessageDetail({{ $contact->id }})" class="text-primary">xem chi tiết</a>
                                    </td>
                                    <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex flex-column gap-1">
                                            <button class="btn btn-sm btn-danger w-100" onclick="deleteContact({{ $contact->id }}, event)" title="Xóa">
                                                <i class="ti ti-trash me-1"></i>Xóa
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Không có tin nhắn liên hệ nào</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                @if($contacts->hasPages())
                    <div class="card-footer">
                        {{ $contacts->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Chi tiết -->
<div class="modal fade" id="messageDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Nội dung tin nhắn</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-control-plaintext" id="modal-content" style="min-height: 200px; background-color: #f8f9fa; padding: 20px; border-radius: 8px; white-space: pre-wrap;"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Form xóa ẩn -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<style>
.status-select {
    border: 2px solid #dc3545;
    color: #dc3545;
    font-weight: 500;
}

.status-select option[value="pending"] { background-color: #dc3545; color: white; }
.status-select option[value="replied"] { background-color: #198754; color: white; }

.card-table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.card-table tbody tr:hover {
    background-color: #f8f9fa;
}

.btn {
    font-size: 0.75rem;
    padding: 0.25rem 0.5rem;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.form-control-plaintext {
    border: 1px solid #dee2e6;
    padding: 0.5rem;
    border-radius: 0.375rem;
    background-color: #fff;
    margin-bottom: 0;
}

.gap-1 {
    gap: 0.25rem !important;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xóa select trạng thái, option trạng thái, hàm markAsReplied, các fetch liên quan đến cập nhật trạng thái, và các đoạn JS/HTML liên quan đến phản hồi.
});

function showMessageDetail(contactId) {
    try {
        const contact = document.querySelector(`tr[data-contact-id="${contactId}"]`);
        if (!contact) {
            console.error('Contact not found:', contactId);
            return;
        }
        
        const message = contact.getAttribute('data-message') || '';

        document.getElementById('modal-content').textContent = message;
        
        const modal = new bootstrap.Modal(document.getElementById('messageDetailModal'));
        modal.show();
    } catch (error) {
        console.error('Error showing message detail:', error);
        alert('Có lỗi xảy ra khi hiển thị chi tiết tin nhắn');
    }
}

function deleteContact(contactId, event) {
    try {
        if (confirm('Bạn có chắc chắn muốn xóa tin nhắn này?')) {
            // Hiển thị loading
            const btn = event.target.closest('button');
            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<i class="ti ti-loader ti-spin"></i>';
            btn.disabled = true;
            
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
    } catch (error) {
        console.error('Error deleting contact:', error);
        alert('Có lỗi xảy ra khi xóa tin nhắn');
    }
}

function showNotification(message, type = 'info') {
    // Tạo notification element
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.body.appendChild(notification);
    
    // Tự động ẩn sau 3 giây
    setTimeout(() => {
        if (notification.parentNode) {
            notification.remove();
        }
    }, 3000);
}
</script>
@endsection 