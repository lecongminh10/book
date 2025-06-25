@extends('layouts.master')

@section('title')
    Trang chủ
@endsection

@section('content')
    <style>
        .book-card {
            border-radius: 18px;
            transition: box-shadow 0.2s, transform 0.2s;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
            background: #fff;
        }

        .book-card:hover {
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.18);
            transform: translateY(-6px) scale(1.03);
            z-index: 2;
        }

        .book-img-wrap {
            background: #f8f9fa;
            border-radius: 18px 18px 0 0;
            min-height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .book-img {
            max-height: 160px;
            width: auto;
            object-fit: contain;
            margin: 0 auto;
            transition: transform 0.2s;
        }

        .book-card:hover .book-img {
            transform: scale(1.07);
        }

        .book-badge {
            font-size: 0.85rem;
            font-weight: 600;
            padding: 0.4em 0.9em;
            border-radius: 12px 0 12px 0;
            box-shadow: 0 2px 8px rgba(255, 193, 7, 0.15);
        }

        .book-title {
            font-size: 1.05rem;
            font-weight: bold;
            color: #222;
            min-height: 2.5em;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .book-author {
            font-size: 0.97rem;
            color: #6c757d;
            min-height: 1.5em;
            margin-bottom: 0.2em;
        }

        @media (max-width: 991px) {
            .col-lg-2 {
                flex: 0 0 33.3333%;
                max-width: 33.3333%;
            }
        }

        @media (max-width: 767px) {

            .col-md-4,
            .col-lg-2 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        @media (max-width: 575px) {

            .col-12,
            .col-sm-6,
            .col-md-4,
            .col-lg-2 {
                flex: 0 0 100%;
                max-width: 100%;
            }

            .book-img-wrap {
                min-height: 120px;
            }

            .book-img {
                max-height: 100px;
            }
        }
    </style>
    <!-- ======= Hero Slider Section ======= -->
    <section id="hero-slider" class="hero-slider">
        <div class="container-md" data-aos="fade-in">
            <div class="row">
                <div class="col-12">
                    <div class="swiper sliderFeaturedPosts">
                        <div class="swiper-wrapper">
                            @if($slide_1)
                                @foreach(explode(',', $slide_1) as $slide)
                                    <div class="swiper-slide">
                                        <a href="single-post.html" class="img-bg d-flex align-items-end"
                                            style="background-image: url('{{ trim($slide) }}');">
                                            <div class="img-bg-inner">
                                                <h2>Slide từ Web Setting</h2>
                                                <p>Nội dung slide được quản lý từ admin.</p>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="custom-swiper-button-next">
                            <span class="bi-chevron-right"></span>
                        </div>
                        <div class="custom-swiper-button-prev">
                            <span class="bi-chevron-left"></span>
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section id="posts_new" class="posts">
        <h5 class="mb-4 text-center fw-bold" style="font-size:1.4rem;">Sách mới nhất</h5>
        <div class="container" data-aos="fade-up">
            <div class="row g-4 justify-content-center">
                @foreach($books as $book)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex align-items-stretch">
                        <div class="card h-100 text-center border-0 shadow-sm book-card position-relative">
                            <div class="position-relative overflow-hidden book-img-wrap">
                                <a href="/book-detail/{{$book['id']}}">
                                    <img src="{{ $book['cover_front'] }}" class="card-img-top book-img"
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
        </div>
    </section>
    <section id="posts_hot" class="posts">
        <h5 class="mb-4 text-center fw-bold" style="font-size:1.4rem;">Sách nỗi bật</h5>
        <div class="container" data-aos="fade-up">
            <div class="row g-4 justify-content-center">
                @foreach($hot_books as $book)
                    <div class="col-12 col-sm-6 col-md-4 col-lg-2 d-flex align-items-stretch">
                        <div class="card h-100 text-center border-0 shadow-sm book-card position-relative">
                            <a href="/book-detail/{{$book['id']}}">
                                <div class="position-relative overflow-hidden book-img-wrap">
                                    <img src="{{ $book['cover_front'] }}" class="card-img-top book-img"
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
        </div>
    </section>
    <section class="news-section my-5">
        <div class="container bg-white rounded shadow-sm p-3">
            <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-3">
                <h3 class="fw-bold mb-0" style="font-size:1.5rem;letter-spacing:1px;">TIN TỨC MỚI NHẤT</h3>
                <a href="#" class="text-danger fw-bold" style="font-size:1rem;">Xem tất cả <span
                        class="ms-1">&rsaquo;</span></a>
            </div>

            <div class="row g-3">
                <div class="col-lg-7">
                    <div class="news-main card border-0 h-100">
                        <img src="{{ $new_post['image'] }}" class="w-100 rounded-top"
                            style="max-height:260px;object-fit:cover;" alt="{{ $new_post['title'] }}">
                        <div class="card-body p-3">
                            <div class="fw-bold text-uppercase text-secondary mb-1" style="font-size:0.95rem;">
                                {{ $new_post['category'] }}
                            </div>
                            <h5 class="fw-bold mb-2" style="font-size:1.15rem;">{{ $new_post['title'] }}</h5>
                            <a href="{{ $new_post['link'] }}" class="text-danger fw-bold">Đọc tiếp</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="news-list">
                        @foreach($four_post as $item)
                            <div class="d-flex border-bottom py-2 align-items-start">
                                <img src="{{ $item['image'] }}" alt="{{ $item['title'] }}"
                                    style="width:60px;height:60px;object-fit:cover;border-radius:8px;" class="me-3">
                                <div class="flex-grow-1">
                                    <div class="fw-bold" style="font-size:1rem;">{{ $item['title'] }}</div>
                                    <a href="{{ $item['link'] }}" class="text-danger small">Đọc tiếp</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <style>
        .news-section {
            background: #fafbfc;
            border-radius: 12px;
        }

        .news-main .card-body {
            background: #fff;
            border-radius: 0 0 12px 12px;
        }

        .news-list {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .news-list .border-bottom:last-child {
            border-bottom: none !important;
        }

        .news-list .fw-bold {
            color: #222;
        }

        @media (max-width: 991px) {
            .news-main img {
                max-height: 180px;
            }
        }

        @media (max-width: 767px) {
            .news-section .row {
                flex-direction: column;
            }

            .news-main,
            .news-list {
                margin-bottom: 1rem;
            }
        }
    </style>

@endsection