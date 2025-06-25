@extends('layouts.master')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Hồ sơ cá nhân</h2>
            
            <!-- Alert Messages -->
            @if(isset($_SESSION['success']))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ $_SESSION['success'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @php unset($_SESSION['success']); @endphp
            @endif
            
            @if(isset($_SESSION['error']))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ $_SESSION['error'] }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @php unset($_SESSION['error']); @endphp
            @endif
            
            <!-- Profile Tabs -->
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if(!isset($_SESSION['active_tab']) || $_SESSION['active_tab'] === 'profile-info') active @endif" 
                            id="profile-info-tab" data-bs-toggle="tab" data-bs-target="#profile-info" type="button" role="tab">
                        <i class="fas fa-user"></i> Thông tin cá nhân
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if(isset($_SESSION['active_tab']) && $_SESSION['active_tab'] === 'borrow-history') active @endif" 
                            id="borrow-history-tab" data-bs-toggle="tab" data-bs-target="#borrow-history" type="button" role="tab">
                        <i class="fas fa-history"></i> Lịch sử mượn sách
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link @if(isset($_SESSION['active_tab']) && $_SESSION['active_tab'] === 'change-password') active @endif" 
                            id="change-password-tab" data-bs-toggle="tab" data-bs-target="#change-password" type="button" role="tab">
                        <i class="fas fa-lock"></i> Đổi mật khẩu
                    </button>
                </li>
            </ul>
            
            <div class="tab-content mt-3" id="profileTabsContent">
                <!-- Profile Information Tab -->
                <div class="tab-pane fade @if(!isset($_SESSION['active_tab']) || $_SESSION['active_tab'] === 'profile-info') show active @endif" 
                     id="profile-info" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Cập nhật thông tin cá nhân</h5>
                        </div>
                        <div class="card-body">
                            <form action="/profile/update" method="POST">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="student_id" class="form-label">Mã sinh viên</label>
                                            <input type="text" class="form-control @if(isset($_SESSION['errors']['student_id'])) is-invalid @endif" 
                                                   id="student_id" name="student_id" value="{{ $user['student_id'] ?? '' }}">
                                            @if(isset($_SESSION['errors']['student_id']))
                                                <div class="invalid-feedback">{{ $_SESSION['errors']['student_id'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Tên đăng nhập *</label>
                                            <input type="text" class="form-control @if(isset($_SESSION['errors']['username'])) is-invalid @endif" 
                                                   id="username" name="username" value="{{ $user['username'] }}" required>
                                            @if(isset($_SESSION['errors']['username']))
                                                <div class="invalid-feedback">{{ $_SESSION['errors']['username'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Email *</label>
                                            <input type="email" class="form-control @if(isset($_SESSION['errors']['email'])) is-invalid @endif" 
                                                   id="email" name="email" value="{{ $user['email'] }}" required>
                                            @if(isset($_SESSION['errors']['email']))
                                                <div class="invalid-feedback">{{ $_SESSION['errors']['email'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="full_name" class="form-label">Họ tên *</label>
                                            <input type="text" class="form-control @if(isset($_SESSION['errors']['full_name'])) is-invalid @endif" 
                                                   id="full_name" name="full_name" value="{{ $user['full_name'] }}" required>
                                            @if(isset($_SESSION['errors']['full_name']))
                                                <div class="invalid-feedback">{{ $_SESSION['errors']['full_name'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="role" class="form-label">Vai trò</label>
                                            <input type="text" class="form-control" value="{{ ucfirst($user['role']) }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="created_at" class="form-label">Ngày tạo tài khoản</label>
                                            <input type="text" class="form-control" value="{{ date('d/m/Y H:i', strtotime($user['created_at'])) }}" readonly>
                                        </div>
                                    </div>
                                </div> -->
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Cập nhật thông tin
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Borrow History Tab -->
                <div class="tab-pane fade @if(isset($_SESSION['active_tab']) && $_SESSION['active_tab'] === 'borrow-history') show active @endif" 
                     id="borrow-history" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Lịch sử mượn sách</h5>
                        </div>
                        <div class="card-body">
                            @if(empty($borrowHistory))
                                <div class="text-center py-4">
                                    <i class="fas fa-book fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Bạn chưa có lịch sử mượn sách nào.</p>
                                </div>
                            @else
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead class="table-light">
                                            <tr>
                                                <th>STT</th>
                                                <th>Tên sách</th>
                                                <th>Tác giả</th>
                                                <th>ISBN</th>
                                                <th>Ngày mượn</th>
                                                <th>Ngày trả</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày đăng ký</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($borrowHistory as $index => $borrow)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $borrow['title'] }}</td>
                                                <td>{{ $borrow['author'] }}</td>
                                                <td>{{ $borrow['isbn'] }}</td>
                                                <td>{{ date('d/m/Y', strtotime($borrow['borrow_date'])) }}</td>
                                                <td>{{ date('d/m/Y', strtotime($borrow['return_date'])) }}</td>
                                                <td>
                                                    @php
                                                        $statusClass = [
                                                            'pending' => 'warning',
                                                            'approved' => 'success',
                                                            'rejected' => 'danger',
                                                            'returned' => 'info'
                                                        ];
                                                        $statusText = [
                                                            'pending' => 'Chờ duyệt',
                                                            'approved' => 'Đã duyệt',
                                                            'rejected' => 'Từ chối',
                                                            'returned' => 'Đã trả'
                                                        ];
                                                    @endphp
                                                    <span class="badge bg-{{ $statusClass[$borrow['status']] }}">
                                                        {{ $statusText[$borrow['status']] }}
                                                    </span>
                                                </td>
                                                <td>{{ date('d/m/Y H:i', strtotime($borrow['created_at'])) }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Change Password Tab -->
                <div class="tab-pane fade @if(isset($_SESSION['active_tab']) && $_SESSION['active_tab'] === 'change-password') show active @endif" 
                     id="change-password" role="tabpanel">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Đổi mật khẩu</h5>
                        </div>
                        <div class="card-body">
                            <form action="/profile/change-password" method="POST">
                                <div class="mb-3">
                                    <label for="current_password" class="form-label">Mật khẩu hiện tại *</label>
                                    <input type="password" class="form-control @if(isset($_SESSION['errors']['current_password'])) is-invalid @endif" 
                                           id="current_password" name="current_password" required>
                                    @if(isset($_SESSION['errors']['current_password']))
                                        <div class="invalid-feedback">{{ $_SESSION['errors']['current_password'] }}</div>
                                    @endif
                                </div>
                                
                                <div class="mb-3">
                                    <label for="new_password" class="form-label">Mật khẩu mới *</label>
                                    <input type="password" class="form-control @if(isset($_SESSION['errors']['new_password'])) is-invalid @endif" 
                                           id="new_password" name="new_password" required>
                                    @if(isset($_SESSION['errors']['new_password']))
                                        <div class="invalid-feedback">{{ $_SESSION['errors']['new_password'] }}</div>
                                    @endif
                                    <div class="form-text">Mật khẩu phải có ít nhất 6 ký tự.</div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="confirm_password" class="form-label">Xác nhận mật khẩu mới *</label>
                                    <input type="password" class="form-control @if(isset($_SESSION['errors']['confirm_password'])) is-invalid @endif" 
                                           id="confirm_password" name="confirm_password" required>
                                    @if(isset($_SESSION['errors']['confirm_password']))
                                        <div class="invalid-feedback">{{ $_SESSION['errors']['confirm_password'] }}</div>
                                    @endif
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-key"></i> Đổi mật khẩu
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@php
    // Clear errors and active tab after displaying
    if (isset($_SESSION['errors'])) {
        unset($_SESSION['errors']);
    }
    if (isset($_SESSION['active_tab'])) {
        unset($_SESSION['active_tab']);
    }
@endphp

@endsection

@section('scripts')
<script>
    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
</script>
@endsection