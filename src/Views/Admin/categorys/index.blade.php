@extends('layouts.master')

@section('title')
Danh sách danh mục
@endsection
@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 category-header-bar">
        <h2 class="fw-bold mb-0">Danh sách Categorys</h2>
        <a href="/admin/categorys/create" class="btn btn-gradient fw-bold">+ Thêm mới</a>
    </div>
    <div class="card shadow-lg p-3 category-table-card">
        <div class="table-responsive">
            <table class="table category-table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th class="text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($categorys as $category)
                    <tr>
                        <td>{{ $category['id'] }}</td>
                        <td>{{ $category['name'] }}</td>
                        <td class="text-center">
                            <a class="btn btn-primary btn-action me-1" href="/admin/categorys/{{$category['id']}}/update">Update</a>
                            <a class="btn btn-warning btn-action me-1" href="/admin/categorys/{{$category['id']}}/show">Show</a>
                            <a onclick="return confirm('Có chắc muốn xóa không')" class="btn btn-danger btn-action" href="/admin/categorys/{{$category['id']}}/delete">Delete</a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
@section('styles')
<style>
    .category-header-bar {
        background: linear-gradient(90deg, #e0e7ff 0%, #f0fdfa 100%);
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(59,130,246,0.07);
        padding: 1.2rem 2rem;
        margin-bottom: 2rem;
    }
    @media (max-width: 700px) {
        .category-header-bar {
            padding: 0.7rem 0.7rem;
            border-radius: 0.7rem;
        }
    }
    .category-table-card {
        border-radius: 1.5rem;
        background: rgba(255,255,255,0.97);
        box-shadow: 0 8px 32px rgba(30,58,138,0.13), 0 1.5px 6px rgba(16,185,129,0.08);
        border: none;
    }
    .category-table {
        border-radius: 1rem;
        overflow: hidden;
        background: #f8fafc;
        box-shadow: 0 2px 8px rgba(59,130,246,0.07);
    }
    .category-table th, .category-table td {
        padding: 0.85rem 1.2rem;
        font-size: 1.08rem;
        vertical-align: middle;
    }
    .category-table th {
        background: #2563eb;
        color: #fff;
        font-weight: 700;
        border: none;
    }
    .category-table tr:hover td {
        background: #e0e7ff;
        transition: background 0.2s;
    }
    .category-table td {
        border-top: 1px solid #e5e7eb;
        color: #334155;
    }
    .btn-gradient {
        background: linear-gradient(90deg, #3b82f6 0%, #10b981 100%);
        color: #fff;
        border: none;
        border-radius: 1.2rem;
        font-size: 1.05rem;
        font-weight: 700;
        padding: 0.6rem 1.3rem;
        letter-spacing: 0.5px;
        box-shadow: 0 4px 16px rgba(16, 185, 129, 0.13);
        transition: all 0.3s cubic-bezier(.4,2,.6,1);
    }
    .btn-gradient:hover {
        background: linear-gradient(90deg, #2563eb 0%, #059669 100%);
        box-shadow: 0 8px 24px rgba(59, 130, 246, 0.18);
        transform: translateY(-1px) scale(1.01);
    }
    .btn-action {
        border-radius: 1.2rem !important;
        font-weight: 600;
        padding: 0.5rem 1.1rem;
        font-size: 1rem;
    }
    .btn-primary.btn-action {
        background: #2563eb;
        border: none;
    }
    .btn-warning.btn-action {
        background: #f59e42;
        border: none;
        color: #fff;
    }
    .btn-danger.btn-action {
        background: #ef4444;
        border: none;
        color: #fff;
    }
    .btn-primary.btn-action:hover {
        background: #1d4ed8;
    }
    .btn-warning.btn-action:hover {
        background: #d97706;
        color: #fff;
    }
    .btn-danger.btn-action:hover {
        background: #b91c1c;
        color: #fff;
    }
    @media (max-width: 700px) {
        .category-table-card {
            padding: 1.2rem 0.5rem;
        }
        .category-table th, .category-table td {
            font-size: 0.98rem;
            padding: 0.6rem 0.5rem;
        }
        .btn-gradient, .btn-action {
            font-size: 0.95rem;
            padding: 0.5rem 0.7rem;
        }
    }
</style>
@endsection
