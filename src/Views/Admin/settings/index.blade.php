@extends('layouts.master')

@section('content')
<div class="container py-5">
    <h2>Danh sách Thiết lập</h2>
    <!-- <a href="/admin/settings/create" class="btn btn-primary mb-3">Thêm mới</a> -->
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Tên</th>
                <th>Giá trị</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($settings as $setting)
            <tr>
                <td>{{ $setting['id'] }}</td>
                <td>{{ $setting['title'] }}</td>
                <td>{{ $setting['name'] }}</td>
                <td>{{ $setting['value'] }}</td>
                <td>
                    <!-- <a href="/admin/settings/show/{{ $setting['id'] }}" class="btn btn-info btn-sm">Xem</a> -->
                    <a href="/admin/settings/update/{{ $setting['id'] }}" class="btn btn-warning btn-sm">Sửa</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
@section('styles')
<style>
    .table {
    width: 100%;
}

.table td {
    max-width: 200px; /* Độ rộng tối đa cho cột, bạn có thể điều chỉnh */
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
@endsection