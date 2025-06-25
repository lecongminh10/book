@extends('layouts.master')

@section('title')
    Cập nhật Bài viết
@endsection

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="card shadow-lg p-4 form-card">
        <h2 class="mb-4 text-center fw-bold">Cập nhật bài viết</h2>
        @if(isset($_SESSION['error']))
            <div class="alert alert-danger">{{ $_SESSION['error'] }}</div>
            @php unset($_SESSION['error']); @endphp
        @endif
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="title" class="form-label">Tiêu đề</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ $post['title'] }}" required>
            </div>
            <div class="mb-3">
                <label for="slug" class="form-label">Slug</label>
                <input type="text" class="form-control" id="slug" name="slug" value="{{ $post['slug'] }}" required>
            </div>
            <div class="mb-3">
                <label for="content_post" class="form-label">Nội dung</label>
                <textarea class="form-control" id="content_post" name="content_post" required rows="3">{{ $post['content'] }}</textarea>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Ảnh đại diện (upload file)</label>
                @if($post['image'])
                    <div class="mb-2">
                        <img src="{{ $post['image'] }}" alt="Ảnh hiện tại" style="max-width:100px;max-height:100px;">
                    </div>
                @endif
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                <input type="hidden" name="old_image" value="{{ $post['image'] }}">
            </div>
            <div class="mb-3">
                <label for="category_id" class="form-label">Danh mục</label>
                <select class="form-control" id="category_id" name="category_id" required>
                    <option value="">-- Chọn danh mục --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat['id'] }}" @if($cat['id'] == $post['category_id']) selected @endif>{{ $cat['name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="user_id" class="form-label">User ID</label>
                <select class="form-control" id="user_id" name="user_id" required>
                    <option value="">-- Chọn người viết --</option>
                    @foreach($users as $cat)
                        <option value="{{ $cat['id'] }}" @if($cat['id'] == $post['user_id']) selected @endif>{{ $cat['full_name'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Trạng thái</label>
                <select class="form-control" id="status" name="status">
                    <option value="draft" @if($post['status'] == 'draft') selected @endif>Nháp</option>
                    <option value="published" @if($post['status'] == 'published') selected @endif>Xuất bản</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Cập nhật</button>
            <a href="/admin/posts" class="btn btn-secondary">Quay lại</a>
        </form>
    </div>
</div>
<style>
    .cke_notifications_area{ display: none; }
</style>
<script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script>
<script>
    CKEDITOR.replace('content_post');
</script>
@endsection
