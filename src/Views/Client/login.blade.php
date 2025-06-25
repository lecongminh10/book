@extends('layouts.master')

@section('title', 'Đăng nhập')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 text-center">
                    <h2 class="card-title">Đăng nhập</h2>
                </div>
                <div class="card-body">
                    @if(isset($error))
                        <div class="alert alert-danger text-center" role="alert">
                            {{ $error }}
                        </div>
                    @endif
                    <form method="POST" action="/login" class="row g-3">
                        @csrf
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
                        <div class="col-12 text-center">
                            <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                        </div>
                        <div class="col-12 text-center">
                            <p class="mb-0">Chưa có tài khoản? <a href="/register" class="text-decoration-none">Đăng ký ngay</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection