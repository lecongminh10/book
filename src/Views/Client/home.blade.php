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
            <!-- Thêm nội dung post nếu cần -->
        </div>
    </div>
</section><!-- End Post Grid Section -->

@endsection