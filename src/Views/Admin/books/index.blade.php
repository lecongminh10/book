@extends('layouts.master')

@section('title')
Danh sách Sách
@endsection

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0">Danh sách Sách</h2>
        <a href="/admin/books/create" class="btn btn-gradient fw-bold">+ Thêm mới</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tiêu đề</th>
                    <th>Tác giả</th>
                    <th>Thể loại</th>
                    <th>Năm XB</th>
                    <th>ISBN</th>
                    <th>Vị trí</th>
                    <th>Bìa trước</th>
                    <th>Bìa sau</th>
                    <th>Tóm tắt</th>
                    <th>Nội dung</th>
                    <th>Nổi bật</th>
                    <th>Ngày tạo</th>
                    <th>Ngày cập nhật</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($books as $book)
                <tr>
                    <td>{{ $book['id'] }}</td>
                    <td>{{ $book['title'] }}</td>
                    <td>{{ $book['author'] }}</td>
                    <td>{{ $book['category_id'] }}</td>
                    <td>{{ $book['publish_year'] }}</td>
                    <td>{{ $book['isbn'] }}</td>
                    <td>{{ strlen($book['location_description']) > 30 ? mb_substr($book['location_description'], 0, 30) . '...' : $book['location_description'] }}</td>
                    <td>
                        @if($book['cover_front'])
                            <img src="/{{ $book['cover_front'] }}" style="width:40px;height:60px;object-fit:cover;">
                        @endif
                    </td>
                    <td>
                        @if($book['cover_back'])
                            <img src="/{{ $book['cover_back'] }}" style="width:40px;height:60px;object-fit:cover;">
                        @endif
                    </td>
                    <td>{{ strlen($book['summary']) > 30 ? mb_substr($book['summary'], 0, 30) . '...' : $book['summary'] }}</td>
                    <td>{{ strlen($book['content']) > 30 ? mb_substr($book['content'], 0, 30) . '...' : $book['content'] }}</td>
                    <td>
                        @if($book['is_featured'])
                            <span class="badge bg-success">Nổi bật</span>
                        @else
                            <span class="badge bg-secondary">Thường</span>
                        @endif
                    </td>
                    <td>{{ $book['created_at'] }}</td>
                    <td>{{ $book['updated_at'] }}</td>
                    <td class="text-center">
                        <a class="btn btn-info btn-action me-1" href="/admin/books/{{ $book['id'] }}/show">Xem</a>
                        <a class="btn btn-primary btn-action me-1" href="/admin/books/{{ $book['id'] }}/update">Sửa</a>
                        <a onclick="return confirm('Bạn có chắc muốn xóa sách này?')" class="btn btn-danger btn-action" href="/admin/books/{{ $book['id'] }}/delete">Xóa</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if(isset($totalPages) && $totalPages > 1)
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center mt-4">
            <li class="page-item @if($currentPage <= 1) disabled @endif">
                <a class="page-link" href="?page={{ $currentPage - 1 }}" tabindex="-1">Prev</a>
            </li>
            @for($i = 1; $i <= $totalPages; $i++)
                <li class="page-item @if($i == $currentPage) active @endif">
                    <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                </li>
            @endfor
            <li class="page-item @if($currentPage >= $totalPages) disabled @endif">
                <a class="page-link" href="?page={{ $currentPage + 1 }}">Next</a>
            </li>
        </ul>
    </nav>
    @endif
</div>
<style>
.btn-action {
    display: inline-block;
    border-radius: 1.2rem !important;
    font-weight: 600;
    padding: 0.4rem 0.9rem;
    font-size: 0.98rem;
    margin-right: 0.15rem;
    margin-bottom: 0.15rem;
    vertical-align: middle;
    white-space: nowrap;
}
.btn-gradient{
    background: linear-gradient(90deg, #0ea5e9 0%, #2563eb 100%);
    color: #000000;
}
.btn-action:last-child {
    margin-right: 0;
}
.btn-info.btn-action {
    background: #0ea5e9;
    border: none;
    color: #fff;
}
.btn-info.btn-action:hover {
    background: #0369a1;
    color: #fff;
}
.btn-primary.btn-action {
    background: #2563eb;
    border: none;
}
.btn-primary.btn-action:hover {
    background: #1d4ed8;
}
.btn-danger.btn-action {
    background: #ef4444;
    border: none;
    color: #fff;
}
.btn-danger.btn-action:hover {
    background: #b91c1c;
    color: #fff;
}
.pagination .page-item.active .page-link {
    background: #2563eb;
    border-color: #2563eb;
    color: #fff;
}
.pagination .page-link {
    color: #2563eb;
}
</style>
@endsection
