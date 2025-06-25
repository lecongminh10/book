@extends('layouts.master')

@section('title', 'Cập nhật danh mục bài viết')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Cập nhật danh mục bài viết</h2>
    @if(isset($_SESSION['error']))
        <div class="alert alert-danger">{{ $_SESSION['error'] }}</div>
        @php unset($_SESSION['error']); @endphp
    @endif
    <form action="" method="POST">
        <input type="hidden" value="{{ $category['id'] }}" name="id">
        <div class="mb-3">
            <label for="name" class="form-label">Tên danh mục</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ $category['name'] }}" required>
        </div>
        <div class="mb-3">
            <label for="slug" class="form-label">Slug</label>
            <input type="text" class="form-control" id="slug" name="slug" value="{{ $category['slug'] }}" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Mô tả</label>
            <textarea class="form-control" id="description" name="description" rows="3">{{ $category['description'] }}</textarea>
        </div>
        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="/admin/category-post" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
