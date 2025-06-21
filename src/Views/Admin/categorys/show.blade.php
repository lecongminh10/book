@extends('layouts.master')

@section('title')
Thông tin Category
@endsection
@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 60vh;">
    <div class="card shadow-lg p-4 category-info-card">
        <h2 class="mb-4 text-center fw-bold">Thông tin Category</h2>
        <div class="table-responsive">
            <table class="table category-info-table">
                <tr>
                    <th>Trường</th>
                    <th>Giá trị</th>
                </tr>
                <tr>
                    <td>ID</td>
                    <td>{{$category['id']}}</td>
                </tr>
                <tr>
                    <td>Tên danh mục</td>
                    <td>{{$category['name']}}</td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection
@section('styles')
<style>
    .category-info-card {
        max-width: 500px;
        width: 100%;
        border-radius: 1.5rem;
        background: rgba(255,255,255,0.97);
        box-shadow: 0 8px 32px rgba(30,58,138,0.13), 0 1.5px 6px rgba(16,185,129,0.08);
        border: none;
        padding: 2.5rem 2rem;
        margin: 0 auto;
    }
    .category-info-table {
        border-radius: 1rem;
        overflow: hidden;
        background: #f8fafc;
        box-shadow: 0 2px 8px rgba(59,130,246,0.07);
    }
    .category-info-table th, .category-info-table td {
        padding: 0.85rem 1.2rem;
        font-size: 1.08rem;
        vertical-align: middle;
    }
    .category-info-table th {
        background: #2563eb;
        color: #fff;
        font-weight: 700;
        border: none;
    }
    .category-info-table tr:not(:first-child):hover {
        background: #e0e7ff;
        transition: background 0.2s;
    }
    .category-info-table td {
        border-top: 1px solid #e5e7eb;
        color: #334155;
    }
    @media (max-width: 700px) {
        .category-info-card {
            padding: 1.2rem 0.5rem;
        }
        .category-info-table th, .category-info-table td {
            font-size: 0.98rem;
            padding: 0.6rem 0.5rem;
        }
    }
</style>
@endsection