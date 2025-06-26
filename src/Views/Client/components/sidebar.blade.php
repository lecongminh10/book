<div class="col-md-3">
    <!-- ======= Sidebar ======= -->
    <div class="aside-block">

        <ul class="nav nav-pills custom-tab-nav mb-4" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="pills-popular-tab" data-bs-toggle="pill"
                    data-bs-target="#pills-popular" type="button" role="tab" aria-controls="pills-popular"
                    aria-selected="true">Phổ biến </button>
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
        </div>
    </div>

    <div class="aside-block">
        <h3 class="aside-title">Danh mục sách</h3>
        <ul class="aside-links list-unstyled">
            @foreach ($categories as $category)
            <li><a href="/categorise/{{ $category['id']}}"><i class="bi bi-chevron-right"></i> {{$category['name']}}</a></li>

            @endforeach
          
        </ul>
    </div><!-- End Categories -->
</div>
