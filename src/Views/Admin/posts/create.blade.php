@extends('layouts.master')

@section('title')
    Cập nhật Sách
@endsection

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 form-card">
        <h2 class="mb-4 text-center fw-bold">Thêm bài viết mới</h2>
        @if(isset($_SESSION['error']))
            <div class="alert alert-danger">{{ $_SESSION['error'] }}</div>
            @php unset($_SESSION['error']); @endphp
        @endif
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug" required>
            </div>
            <div class="mb-3">
                <label for="content_post" class="form-label">Nội dung</label>
                <textarea class="form-control" id="content_post" name="content_post" required  rows="3" placeholder="Nhập bài viết"></textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh đại diện (upload file)</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Danh mục</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat['id'] }}">{{ $cat['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <option value="">-- Chọn người viết --</option>
                    @foreach($users as $cat)
                        <option value="{{ $cat['id'] }}">{{ $cat['full_name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" id="status" name="status">
                    <option value="draft">Nháp</option>
                    <option value="published">Xuất bản</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Lưu</button>
            <a href="/admin/posts" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
<style>
    .cke_notifications_area{
        display: none;
    }
</style>
<script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content_post');
</script>
@endsection
