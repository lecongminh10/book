@extends('layouts.master')

@section('title')
    Danh sách bài viết
@endsection

@section('content')
<div class="container mt-5 mb-5">
    <h2 class="mb-4">Danh sách bài viết</h2>
    @if(empty($posts))
        <p class="text-muted">Không có bài viết nào.</p>
    @else
    <div class="row g-4 justify-content-center" style="margin-bottom: 100px">
        @foreach($posts as $post)
            <div class="col-12 col-md-6 col-lg-4 d-flex align-items-stretch">
                <div class="card h-100 border-0 shadow-sm">
                    <a href="/post/{{ $post['id'] }}">
                        <img src="{{ $post['image'] ?? '/assets/client/assets/img/default.jpg' }}" class="card-img-top" alt="{{ $post['title'] }}">
                    </a>
                    <div class="card-body">
                        <div class="post-meta mb-2">
                            <span class="badge bg-primary">{{ $post['category_name'] ?? 'Chưa phân loại' }}</span>
                            <span class="mx-1">&bullet;</span>
                            <span class="text-muted">{{ date('d/m/Y', strtotime($post['created_at'])) }}</span>
                        </div>
                        <h5 class="card-title"><a href="/post/{{ $post['id'] }}" class="text-decoration-none">{{ $post['title'] }}</a></h5>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    {{-- Pagination --}}
    @if($totalPages > 1)
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">
            {{-- Previous --}}
            <li class="page-item {{ $page <= 1 ? 'disabled' : '' }}">
                <a class="page-link" href="?page={{ $page - 1 }}" tabindex="-1">&laquo; Trước</a>
            </li>
            {{-- Page numbers --}}
            @for($i = 1; $i <= $totalPages; $i++)
                <li class="page-item {{ $i == $page ? 'active' : '' }}">
                    <a class="page-link" href="?page={{ $i }}">{{ $i }}</a>
                </li>
            @endfor
            {{-- Next --}}
            <li class="page-item {{ $page >= $totalPages ? 'disabled' : '' }}">
                <a class="page-link" href="?page={{ $page + 1 }}">Sau &raquo;</a>
            </li>
        </ul>
    </nav>
    @endif
    @endif
</div>
@endsection
