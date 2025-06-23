@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2>Chi tiết Thiết lập</h2>
    @if(isset($error))
        <div class="alert alert-danger">{{ $error }}</div>
    @endif
    <p><strong>ID:</strong> {{ $setting['id'] }}</p>
    <p><strong>Tiêu đề:</strong> {{ $setting['title'] }}</p>
    <p><strong>Tên:</strong> {{ $setting['name'] }}</p>
    <p><strong>Giá trị:</strong> {{ $setting['value'] }}</p>
    <a href="/admin/settings/update/{{ $setting['id'] }}" class="btn btn-warning">Sửa</a>
    <a href="/admin/settings" class="btn btn-secondary">Quay lại</a>
</div>
@endsection