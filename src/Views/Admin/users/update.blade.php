@extends('layouts.master')

@section('title')
    Cập nhật người dùng
@endsection
@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 form-card">
        <h2 class="mb-4 text-center fw-bold">Cập nhật Người Dùng</h2>
        @if(!empty($_SESSION['success']))
            <div class="alert alert-success text-center">
                {{ $_SESSION['success'] }}
            </div>
            @php
                $_SESSION['success'] = null;
            @endphp
        @endif
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Mã sinh viên</label>
                        <input type="text" class="form-control custom-input" id="student_id" name="student_id" value="{{$user['student_id']}}" placeholder="Nhập mã sinh viên (nếu có)">
                    </div>
                    <div class="mb-3">
                        <label for="username" class="form-label">Tên đăng nhập</label>
                        <input type="text" class="form-control custom-input" id="username" name="username" required value="{{$user['username']}}" placeholder="Nhập tên đăng nhập">
                    </div>
                    <div class="mb-3">
                        <label for="full_name" class="form-label">Họ và tên</label>
                        <input type="text" class="form-control custom-input" id="full_name" name="full_name" required value="{{$user['full_name']}}" placeholder="Nhập họ và tên">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control custom-input" id="email" name="email" required value="{{$user['email']}}" placeholder="Nhập email">
                    </div>
                    <div class="mb-3">
                        <label for="role" class="form-label">Vai trò</label>
                        <select class="form-select custom-input" id="role" name="role" required>
                            <option value="user" {{$user['role']==='user' ? 'selected' : ''}}>User</option>
                            <option value="admin" {{$user['role']==='admin' ? 'selected' : ''}}>Admin</option>
                        </select>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-gradient w-100 fw-bold py-2 mt-3">Cập nhật</button>
        </form>
    </div>
</div>
@endsection
@section('styles')
<style>
    .form-card {
        max-width: 900px;
        width: 100%;
        border-radius: 2rem;
        background: rgba(255, 255, 255, 0.85);
        box-shadow: 0 12px 40px rgba(30, 58, 138, 0.15), 0 2px 8px rgba(16, 185, 129, 0.08);
        border: none;
        padding: 2.5rem 2rem;
        backdrop-filter: blur(4px);
        transition: box-shadow 0.3s, transform 0.3s;
    }
    .form-card:hover {
        box-shadow: 0 20px 60px rgba(30, 58, 138, 0.18), 0 4px 16px rgba(16, 185, 129, 0.12);
        transform: translateY(-2px) scale(1.01);
    }
    h2 {
        color: #2563eb;
        font-size: 2.1rem;
        font-weight: 800;
        letter-spacing: 0.5px;
        margin-bottom: 1.5rem;
        text-align: center;
        text-shadow: 0 2px 8px rgba(59, 130, 246, 0.08);
    }
    .form-label {
        font-weight: 700;
        color: #334155;
        font-size: 1rem;
        margin-bottom: 0.5rem;
    }
    .custom-input {
        border-radius: 1.2rem !important;
        border: 1.5px solid #cbd5e1 !important;
        padding: 1rem 1.2rem;
        font-size: 1.08rem;
        background: #f1f5f9;
        transition: all 0.3s cubic-bezier(.4,2,.6,1);
        box-shadow: 0 1px 3px rgba(59, 130, 246, 0.03);
        margin-bottom: 0.5rem;
        height: 48px;
    }
    .custom-input:focus {
        border-color: #38bdf8 !important;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.13);
        background: #fff;
        outline: none;
    }
    .btn-gradient {
        background: linear-gradient(90deg, #3b82f6 0%, #10b981 100%);
        color: #fff;
        border: none;
        border-radius: 1.2rem;
        font-size: 1.13rem;
        font-weight: 700;
        padding: 1rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.13);
        transition: all 0.3s cubic-bezier(.4,2,.6,1);
    }
    .btn-gradient:hover {
        background: linear-gradient(90deg, #2563eb 0%, #059669 100%);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.18);
        transform: translateY(-1px) scale(1.01);
    }
    .btn-gradient:active {
        transform: translateY(0);
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.10);
    }
    @media (max-width: 600px) {
        .form-card {
            padding: 1.2rem 0.5rem;
            margin: 0 0.5rem;
        }
        h2 {
            font-size: 1.4rem;
        }
        .custom-input {
            font-size: 0.98rem;
            padding: 0.7rem 0.8rem;
        }
        .btn-gradient {
            font-size: 1rem;
            padding: 0.7rem;
        }
    }
    .form-select.custom-input {
        font-family: inherit !important;
        font-size: 1.08rem !important;
        letter-spacing: normal !important;
        text-indent: 0 !important;
        text-transform: none !important;
        padding-left: 1.2rem !important;
        padding-right: 2.2rem !important;
        width: 100% !important;
        min-width: 120px;
        box-sizing: border-box;
        overflow: visible !important;
        background-clip: padding-box;
        height: auto !important;
    }
</style>
@endsection