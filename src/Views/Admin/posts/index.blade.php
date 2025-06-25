@extends('layouts.master')

@section('title', 'Danh sách bài viết')

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
        <h2 class="mb-0">Danh sách bài viết</h2>
        <a href="/admin/posts/create" class="btn btn-primary">+ Thêm bài viết</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Slug</th>
                <th>Ảnh</th>
                <th>Danh mục</th>
                <th>Trạng thái</th>
                <th>Ngày tạo</th>
                <th>Ngày cập nhật</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($posts as $key=> $post)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $post['title'] }}</td>
                    <td>{{ $post['slug'] }}</td>
                    <td>
                        @if($post['image'])
                            <img src="{{ $post['image'] }}" alt="{{ $post['title'] }}" style="max-width:60px;max-height:60px;">
                        @endif
                    </td>
                    <td>{{ $post['category_name'] ?? '' }}</td>
                    <td>{{ $post['status'] }}</td>
                    <td>{{ $post['created_at'] }}</td>
                    <td>{{ $post['updated_at'] }}</td>
                    <td>
                        <a href="/admin/posts/{{ $post['id'] }}/update" class="btn btn-sm btn-warning me-1" title="Sửa">Sữa</i></a>
                        <a href="/admin/posts/{{ $post['id'] }}/delete" class="btn btn-sm btn-danger" title="Xóa" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Không có bài viết nào.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @if(isset($totalPages) && $totalPages > 1)
        <nav aria-label="Page navigation">
            <ul class="pagination justify-content-center">
                <li class="page-item @if($page <= 1) disabled @endif">
                    <a class="page-link" href="?page={{ $page - 1 }}" tabindex="-1">Previous</a>
                </li>
                @for($i = 1; $i <= $totalPages; $i++)
                    <li class="page-item @if($i == $page) active @endif">
                        <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                    </li>
                @endfor
                <li class="page-item @if($page >= $totalPages) disabled @endif">
                    <a class="page-link" href="?page={{ $page + 1 }}">Next</a>
                </li>
            </ul>
        </nav>
    @endif
</div>
@endsection
