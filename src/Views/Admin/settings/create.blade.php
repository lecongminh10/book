@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2>Thêm Thiết lập</h2>
    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif
    <form method="POST" action="/admin/settings/create">
        @csrf
        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Tên</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="value" class="form-label">Giá trị</label>
            <textarea class="form-control" id="value" name="value" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Thêm</button>
    </form>
</div>
@endsection