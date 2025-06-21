<div class="col-md-3">
    <!-- ======= Sidebar ======= -->
    <div class="aside-block">

        <ul class="nav nav-pills custom-tab-nav mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-popular-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-popular" type="button" role="tab" aria-controls="pills-popular"
                    aria-selected="true">Phổ biến </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="pills-trending-tab" data-bs-toggle="pill" data-bs-target="#pills-trending"
                    type="button" role="tab" aria-controls="pills-trending" aria-selected="false">Xu hướng
                </button>
            </li>

        </ul>

        <div class="tab-content" id="pills-tabContent">

            <!-- Popular -->
            <div class="tab-pane fade show active" id="pills-popular" role="tabpanel"
                aria-labelledby="pills-popular-tab">

                @include('components.post-entry1', [
                    'posts' =>$poststitileTradding,
                    'hiddenAuthor' => false, 
                    'anh'=> false
                     
                ])
            </div> <!-- End Popular -->

            <!-- Trending -->
            <div class="tab-pane fade" id="pills-trending" role="tabpanel" aria-labelledby="pills-trending-tab">
                @include('components.post-entry1', [
                    'posts' =>$poststitileTradding2,
                    'hiddenAuthor' => false, 
                     
                ])
            </div> <!-- End Trending -->


        </div>
    </div>

    <div class="aside-block">
        <h3 class="aside-title">Video</h3>
        <div class="video-post">
            <a href="https://www.youtube.com/watch?v=AiFfDjmd0jU" class="glightbox link-video">
                <span class="bi-play-fill"></span>
                <img src="assets/img/post-landscape-5.jpg" alt="" class="img-fluid">
            </a>
        </div>
    </div><!-- End Video -->

    <div class="aside-block">
        <h3 class="aside-title">Categories</h3>
        <ul class="aside-links list-unstyled">
            @foreach ($categories as $category)
            <li><a href="/categorise/{{ $category['id']}}"><i class="bi bi-chevron-right"></i> {{$category['name']}}</a></li>

            @endforeach
          
        </ul>
    </div><!-- End Categories -->
</div>
