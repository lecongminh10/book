@extends('layouts.master')

@section('title')
    Danh sách người dùng
@endsection

@section('content')
<style>
    .custom-table thead tr th {
        background: linear-gradient(90deg, #5a8dee 0%, #39c2b5 100%) !important;
        color: #fff !important;
        border: none;
        font-size: 1.08rem;
        letter-spacing: 0.5px;
    }
    .custom-table {
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 24px 0 rgba(90,141,238,0.07);
    }
    .custom-table tbody tr:hover {
        background: #f4f8fb !important;
        transition: background 0.2s;
    }
    .badge-role-admin {
        background: #5a8dee;
        color: #fff;
        font-weight: 600;
        border-radius: 12px;
        padding: 6px 18px;
        font-size: 1rem;
        letter-spacing: 1px;
    }
    .badge-role-client, .badge-role-user {
        background: #39c2b5;
        color: #fff;
        font-weight: 600;
        border-radius: 12px;
        padding: 6px 18px;
        font-size: 1rem;
        letter-spacing: 1px;
    }
    .btn {
        border-radius: 8px !important;
        font-weight: 500;
    }
    .btn-primary { background: #5a8dee; border: none; }
    .btn-primary:hover { background: #3a6fd8; }
    .btn-warning { background: #ffc542; border: none; color: #fff; }
    .btn-warning:hover { background: #e1a800; color: #fff; }
    .btn-danger { background: #ff6b6b; border: none; }
    .btn-danger:hover { background: #d63a3a; }
    .user-header-bar {
        background: linear-gradient(90deg, #e0e7ff 0%, #f0fdfa 100%);
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(59,130,246,0.07);
        padding: 1.2rem 2rem;
        margin-bottom: 2rem;
    }
    @media (max-width: 700px) {
        .user-header-bar {
            padding: 0.7rem 0.7rem;
            border-radius: 0.7rem;
        }
    }
</style>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 user-header-bar">
        <h1 class="fw-bold" style="font-size:2.2rem; color:#3a3a3a;">Danh sách người dùng</h1>
        <a href="/admin/users/create" class="btn btn-success btn-lg shadow-sm">
            <i class="fas fa-plus me-2"></i>Thêm mới
        </a>
    </div>
    <div class="table-responsive">
        @if(!empty($_SESSION['success']))
            <div class="alert alert-success text-center">
                {{ $_SESSION['success'] }}
            </div>
            @php
                $_SESSION['success'] = null;
            @endphp
        @endif
        <table class="table custom-table table-hover align-middle bg-white">
            <thead>
                <tr>    
                    <th scope="col">STTt</th>
                    <th scope="col">Tên đăng nhập</th>
                    <th scope="col">Tên đầy đủ</th>
                    <th scope="col">Email</th>
                    <th scope="col">Quyền</th>
                    <th scope="col" class="text-center">Hành động</th>
                </tr>
            </thead>
            <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user['id'] }}</td>
                    <td>{{ $user['username'] }}</td>
                    <td>{{ $user['full_name'] }}</td>
                    <td>{{ $user['email'] }}</td>
                    <td>
                        @if(strtolower($user['role']) === 'admin')
                            <span class="badge badge-role-admin">Quản trị</span>
                        @elseif(strtolower($user['role']) === 'client')
                            <span class="badge badge-role-client">Người dùng</span>
                        @else
                            <span class="badge badge-role-client">Tác giả</span>
                        @endif
                    </td>
                    <td class="text-center">
                        <a class="btn btn-primary btn-sm me-1" href="/admin/users/{{$user['id']}}/update" title="Cập nhật">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a class="btn btn-warning btn-sm me-1" href="/admin/users/{{$user['id']}}/show" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a onclick="return confirm('Có chắc muốn xóa không?')" class="btn btn-danger btn-sm" href="/admin/users/{{$user['id']}}/delete" title="Xóa">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
@endpush