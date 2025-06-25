@extends('layouts.master')

@section('title')
    Chi tiết Sách
@endsection

@section('content')
<style>
    .imaeg{
        position: relative;
    }
    .cover-image-ts{
        position: absolute;
        top: 5px;
        left: 10px;
        margin-left: 5px;
    }
</style>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">
            <div class="card shadow border-0 rounded-4 overflow-hidden animate__animated animate__fadeIn">
                <div class="row g-0">
                    <div class="col-md-4 bg-light d-flex flex-column align-items-center justify-content-center p-3 imaeg">
                        <div class="cover-image-ts">
                            <img src="/{{ $book['cover_front'] ?? 'assets/img/default-book.png' }}" alt="Bìa trước" class="img-fluid rounded shadow mb-3 border border-2" >
                                @if(!empty($book['cover_back']))
                                    <img src="/{{ $book['cover_back'] }}" alt="Bìa sau" class="img-fluid rounded border border-1">
                                @endif
                        </div>
                    </div>
                    <div class="col-md-8 p-4">
                        <h2 class="fw-bold mb-2 text-primary">{{ $book['title'] }}</h2>
                        <div class="mb-2">
                            <span class="badge bg-gradient-primary bg-primary bg-opacity-75 me-2">{{ $book['category_name'] }}</span>
                            @if($book['is_featured'])
                                <span class="badge bg-success">Nổi bật</span>
                            @endif
                        </div>
                        <ul class="list-unstyled mb-3">
                            <li><strong>Tác giả:</strong> {{ $book['author'] }}</li>
                            <li><strong>Năm XB:</strong> {{ $book['publish_year'] }}</li>
                            <li><strong>ISBN:</strong> {{ $book['isbn'] }}</li>
                            <li><strong>Kệ sách:</strong> {{ $book['location_description'] }}</li>
                            <li><strong>Vị trí trong kệ:</strong> {{ $book['shelf_position_title'] ?? 'Không xác định' }}</li>
                        </ul>
                        <div class="mb-3">
                            <h6 class="fw-bold mb-1">Tóm tắt</h6>
                            <div class="text-muted small">{{ $book['summary'] }}</div>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1">Nội dung</h6>
                            <div class="text-muted small">{!! $book['content'] !!}</div>
                        </div>
                        <div class="mt-4 d-flex gap-2">
                            <a href="/admin/books" class="btn btn-outline-secondary rounded-pill px-4">Quay lại</a>
                            <a href="/admin/books/{{ $book['id'] }}/update" class="btn btn-primary rounded-pill px-4">Chỉnh sửa</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
