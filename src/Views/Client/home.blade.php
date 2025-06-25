@extends('layouts.master')

@section('title')
    Trang chủ
@endsection

@section('content')
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
                                    <a href="single-post.html" class="img-bg d-flex align-items-end" style="background-image: url('{{ trim($slide) }}');">
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
</section><!-- End Hero Slider Section -->

<!-- ======= Post Grid Section ======= -->
<section id="posts" class="posts">
    <div class="container" data-aos="fade-up">
        <div class="row g-5">
            @foreach($books as $book)
            <div class="col-md-4 col-lg-2 d-flex align-items-stretch">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="position-relative">
                        <img src="{{ $book['cover_front'] }}" class="card-img-top" alt="{{ $book['title'] }}">
                        @if ($book['is_featured']==1)
                            <span class="badge bg-warning text-dark position-absolute top-0 start-0 m-2">Nổi bật</span>
                        @endif
                    </div>
                    <div class="card-body p-2">
                        <h6 class="card-title mb-1" style="font-size:1rem;font-weight:bold;">{{ $book['title'] }}</h6>
                        <div class="text-secondary mb-1" style="font-size:0.95rem;">{{ $book['author'] }}</div>
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
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section><!-- End Post Grid Section -->

@endsection