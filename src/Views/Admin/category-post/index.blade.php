@extends('layouts.master')

@section('title', 'Danh mục bài viết')

@section('content')
<div class="container mt-4">
    @if(isset($_SESSION['success']))
        <div class="alert alert-success">{{ $_SESSION['success'] }}</div>
        @php unset($_SESSION['success']); @endphp
    @endif
    @if(isset($_SESSION['error']))
        <div class="alert alert-danger">{{ $_SESSION['error'] }}</div>
        @php unset($_SESSION['error']); @endphp
    @endif
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="mb-0">Danh mục bài viết</h2>
        <a href="/admin/category-post/create" class="btn btn-primary">+ Thêm danh mục bài viết</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tên</th>
                <th>Slug</th>
                <th>Mô tả</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($categories as $key=> $category)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $category['name'] }}</td>
                    <td>{{ $category['slug'] }}</td>
                    <td>{{ $category['description'] }}</td>
                    <td>{{ $category['created_at'] }}</td>
                    <td>{{ $category['updated_at'] }}</td>
                    <td>
                        <a href="/admin/category-post/{{ $category['id'] }}/update" class="btn btn-sm btn-warning">Sửa</a>
                        <a href="/admin/category-post/{{ $category['id'] }}/delete" class="btn btn-sm btn-danger" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Không có danh mục nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
