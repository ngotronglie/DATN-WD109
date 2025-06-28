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
                                <th>ID Tài khoản</th>
                                <th>Họ và tên</th>
                                <th>Email</th>
                                <th>Tiêu đề</th>
                                <th>Nội dung</th>
                                <th>Ngày gửi</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge bg-primary">#1</span></td>
                                <td><span class="badge bg-azure">1</span></td>
                                <td>Nguyễn Văn A</td>
                                <td>nguyenvana@example.com</td>
                                <td>Hỏi về sản phẩm</td>
                                <td>
                                    Tôi muốn hỏi về sản phẩm iPhone 15 Pro Max...
                                    <a href="#" onclick="showMessageDetail('Tôi muốn hỏi về sản phẩm iPhone 15 Pro Max, có thể tư vấn thêm không?')" class="text-primary">xem chi tiết</a>
                                </td>
                                <td>15/01/2024</td>
                                <td>
                                    <select class="form-select form-select-sm status-select">
                                        <option value="chua-doc" selected style="background-color: #dc3545; color: white;">Chưa đọc</option>
                                        <option value="dang-xu-ly" style="background-color: #fd7e14; color: white;">Đang xử lý</option>
                                        <option value="da-phan-hoi" style="background-color: #198754; color: white;">Đã phản hồi</option>
                                    </select>
                                </td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        <button class="btn btn-sm btn-success w-100">
                                            <i class="ti ti-mail me-1"></i>Phản hồi
                                        </button>
                                        <button class="btn btn-sm btn-danger w-100">
                                            <i class="ti ti-trash me-1"></i>Xóa
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
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
                <div class="form-control-plaintext" id="modal-content" style="min-height: 200px; background-color: #f8f9fa; padding: 20px; border-radius: 8px; white-space: pre-wrap;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
.status-select {
    border: 2px solid #dc3545;
    color: #dc3545;
    font-weight: 500;
}

.status-select option[value="chua-doc"] { background-color: #dc3545; color: white; }
.status-select option[value="dang-xu-ly"] { background-color: #fd7e14; color: white; }
.status-select option[value="da-phan-hoi"] { background-color: #198754; color: white; }

.card-table th {
    background-color: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
}

.card-table tbody tr:hover {
    background-color: #f8f9fa;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelects = document.querySelectorAll('.status-select');
    
    statusSelects.forEach(select => {
        select.addEventListener('change', function() {
            const value = this.value;
            this.style.borderColor = '';
            this.style.color = '';
            
            switch(value) {
                case 'chua-doc':
                    this.style.borderColor = '#dc3545';
                    this.style.color = '#dc3545';
                    break;
                case 'dang-xu-ly':
                    this.style.borderColor = '#fd7e14';
                    this.style.color = '#fd7e14';
                    break;
                case 'da-phan-hoi':
                    this.style.borderColor = '#198754';
                    this.style.color = '#198754';
                    break;
            }
        });
        
        select.dispatchEvent(new Event('change'));
    });
});

function showMessageDetail(content) {
    document.getElementById('modal-content').textContent = content;
    const modal = new bootstrap.Modal(document.getElementById('messageDetailModal'));
    modal.show();
}
</script>
@endsection 