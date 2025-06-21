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
                        <div class="swiper-slide">
                            <a href="single-post.html" class="img-bg d-flex align-items-end" style="background-image: url('./assets/client/assets/img/post-slide-1.jpg');">
                                <div class="img-bg-inner">
                                    <h2>The Best Homemade Masks for Face (keep the Pimples Away)</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem neque est mollitia! Beatae minima assumenda repellat harum vero, officiis ipsam magnam obcaecati cumque maxime inventore repudiandae quidem necessitatibus rem atque.</p>
                                </div>
                            </a>
                        </div>

                        <div class="swiper-slide">
                            <a href="single-post.html" class="img-bg d-flex align-items-end" style="background-image: url('./assets/client/assets/img/post-slide-2.jpg');">
                                <div class="img-bg-inner">
                                    <h2>17 Pictures of Medium Length Hair in Layers That Will Inspire Your New Haircut</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem neque est mollitia! Beatae minima assumenda repellat harum vero, officiis ipsam magnam obcaecati cumque maxime inventore repudiandae quidem necessitatibus rem atque.</p>
                                </div>
                            </a>
                        </div>

                        <div class="swiper-slide">
                            <a href="single-post.html" class="img-bg d-flex align-items-end" style="background-image: url('./assets/client/assets/img/post-slide-3.jpg');">
                                <div class="img-bg-inner">
                                    <h2>13 Amazing Poems from Shel Silverstein with Valuable Life Lessons</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem neque est mollitia! Beatae minima assumenda repellat harum vero, officiis ipsam magnam obcaecati cumque maxime inventore repudiandae quidem necessitatibus rem atque.</p>
                                </div>
                            </a>
                        </div>

                        <div class="swiper-slide">
                            <a href="single-post.html" class="img-bg d-flex align-items-end" style="background-image: url('./assets/client/assets/img/post-slide-4.jpg');">
                                <div class="img-bg-inner">
                                    <h2>9 Half-up/half-down Hairstyles for Long and Medium Hair</h2>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Quidem neque est mollitia! Beatae minima assumenda repellat harum vero, officiis ipsam magnam obcaecati cumque maxime inventore repudiandae quidem necessitatibus rem atque.</p>
                                </div>
                            </a>
                        </div>
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

            {{-- Tradding 1 --}}

            <div class="col-lg-4">
            
            @include('components.post-entry1', ['post' =>  $postFirstLatest])

            </div>

            <div class="col-lg-8">
                <div class="row g-5">

                    @foreach ($postTop6Chunk as $item)
                    <div class="col-lg-4 border-start custom-border">
                        @foreach($item as $post)
                            @include('components.post-entry1' , [
                                'post' => $post,
                                'hiddenAuthor' => false, 
                                'hiddenExcerpt' => false
                                ])
                        @endforeach
                    </div>
                    @endforeach


                    <!-- Trending Section -->
                    <div class="col-lg-4">

                        <div class="trending">
                            <h3>Trending</h3>

                            <ul class="trending-post">
                                @foreach ($postTredding as $post)
                                <li>
                                    <a href="/post/{{$post['id']}}">
                                        <span class="number">1</span>
                                        <h3>{{$post['title']}}</h3>
                                        <span class="author">Lượt xem  : {{$post['view']}}</span>
                                    </a>
                                </li>
                                @endforeach

                            </ul>
                        </div>

                    </div> <!-- End Trending Section -->
                </div>
            </div>

        </div> <!-- End .row -->
    </div>
</section> <!-- End Post Grid Section -->

<!-- ======= Culture Category Section ======= -->


    @include('components.category-section')
<!-- End Culture Category Section -->



    
@endsection