@extends('layouts.master')

@section('title', 'Đăng ký')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 text-center">
                    <h2 class="card-title">Đăng ký</h2>
                </div>
                <div class="card-body">
                    @if(isset($_SESSION['user']))
                        <div class="alert alert-warning text-center" role="alert">
                            Bạn đã đăng nhập. Không thể đăng ký tài khoản mới!
                        </div>
                    @elseif(isset($error))
                        <div class="alert alert-danger text-center" role="alert">
                            {{ $error }}
                        </div>
                    @endif
                    <form method="POST" action="/register" class="row g-3" @if(isset($_SESSION['user'])) style="display:none;" @endif>
                        @csrf
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="name" name="name" placeholder="Tên" required>
                                <label for="name">Tên</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                                <label for="email">Email</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Mật khẩu" required>
                                <label for="password">Mật khẩu</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Mã sinh viên (tùy chọn)">
                                <label for="student_id">Mã sinh viên</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="username" name="username" placeholder="Tên đăng nhập (tùy chọn)" required>
                                <label for="username">Tên đăng nhập</label>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-floating">
                                <input type="text" class="form-control" id="full_name" name="full_name" placeholder="Họ và tên đầy đủ (tùy chọn)">
                                <label for="full_name">Họ và tên đầy đủ</label>
                            </div>
                        </div>
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                        </div>
                        <div class="col-12 text-center">
                            <p class="mb-0">Đã có tài khoản? <a href="/login" class="text-decoration-none">Đăng nhập ngay</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection