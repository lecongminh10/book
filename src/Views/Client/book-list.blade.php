@extends('layouts.master')

@section('title')
    Sách theo danh mục #{{ $categoryId }}
@endsection

@section('content')
<div class="container book-list-container mt-5">
    <h2>Sách theo danh mục #{{ $categoryId }}</h2>
    @if(empty($books))
        <p class="text-muted">Không có sách nào trong danh mục này.</p>
    @else
    <div class="row g-4 justify-content-center">
        @foreach($books as $book)
            <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex align-items-stretch">
                <div class="card h-100 text-center border-0 shadow-sm book-card position-relative">
                    <a href="/book-detail/{{$book['id']}}">
                        <div class="position-relative overflow-hidden book-img-wrap">
                            <img src="/{{ $book['cover_front'] }}" class="card-img-top book-img"
                                alt="{{ $book['title'] }}">
                            @if ($book['is_featured'] == 1)
                                <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2 book-badge">Nổi
                                    bật</span>
                            @endif
                        </div>
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1 book-title">{{ $book['title'] }}</h6>
                            <div class="text-secondary mb-1 book-author">{{ $book['author'] }}</div>
                            <div class="mb-1">
                                @for($i = 0; $i < 5; $i++)
                                    @if($i < $book['average_rating'])
                                        <span class="text-warning">&#9733;</span>
                                    @else
                                        <span class="text-secondary">&#9733;</span>
                                    @endif
                                @endfor
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
@endsection