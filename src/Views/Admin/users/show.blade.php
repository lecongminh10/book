@extends('layouts.master')

@section('title')
    Thông tin người dùng
@endsection
@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 user-info-card">
        <h2 class="mb-4 text-center fw-bold">Thông tin Người Dùng</h2>
        <div class="table-responsive">
            <table class="table user-info-table">
                <tr>
                    <th>Tên trường</th>
                    <th>Giá trị</th>
                </tr>
                <tr>
                    <td>ID</td>
                    <td>{{$user['id']}}</td>
                </tr>
                <tr>
                    <td>Mã sinh viên</td>
                    <td>{{$user['student_id']}}</td>
                </tr>
                <tr>
                    <td>Tên đăng nhập</td>
                    <td>{{$user['username']}}</td>
                </tr>
                <tr>
                    <td>Họ và tên</td>
                    <td>{{$user['full_name']}}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td>{{$user['email']}}</td>
                </tr>
                <tr>
                    <td>Vai trò</td>
                    <td>{{$user['role']}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
@section('styles')
<style>
    .user-info-card {
        max-width: 600px;
        width: 100%;
        border-radius: 1.5rem;
        background: rgba(255,255,255,0.95);
        box-shadow: 0 8px 32px rgba(30,58,138,0.13), 0 1.5px 6px rgba(16,185,129,0.08);
        border: none;
        padding: 2.5rem 2rem;
        margin: 0 auto;
    }
    .user-info-table {
        border-radius: 1rem;
        overflow: hidden;
        background: #f8fafc;
        box-shadow: 0 2px 8px rgba(59,130,246,0.07);
    }
    .user-info-table th, .user-info-table td {
        padding: 0.85rem 1.2rem;
        font-size: 1.08rem;
        vertical-align: middle;
    }
    .user-info-table th {
        background: #2563eb;
        color: #fff;
        font-weight: 700;
        border: none;
    }
    .user-info-table tr:not(:first-child):hover {
        background: #e0e7ff;
        transition: background 0.2s;
    }
    .user-info-table td {
        border-top: 1px solid #e5e7eb;
        color: #334155;
    }
    @media (max-width: 700px) {
        .user-info-card {
            padding: 1.2rem 0.5rem;
        }
        .user-info-table th, .user-info-table td {
            font-size: 0.98rem;
            padding: 0.6rem 0.5rem;
        }
    }
</style>
@endsection