

@foreach ($categories as $category)
<section class="category-section">
    <div class="container" data-aos="fade-up">
    
        <div class="section-header d-flex justify-content-between align-items-center mb-5">
            <h2>{{ $category['name']}}</h2>
            <div><a href="/categorise/{{ $category['id']}}" class="more">Xem thêm </a></div>
        </div>
    
        <div class="row">
            <div class="col-md-9">
    
                @foreach ($postsByCategory[$category['id']] as $post)
                <div class="d-lg-flex post-entry-2">
                    <a href="/post/{{$post['id']}}" class="me-4 thumbnail mb-4 mb-lg-0 d-inline-block">
                        <img src="{{ $post['image'] }}" alt="" class="img-fluid">
                    </a>
                    <div>
                        <div class="post-meta"><span class="date">{{ $category['name'] }}</span> <span class="mx-1">&bullet;</span> <span>{{ $post['created_at'] }}</span></div>
                        <h3><a href="/post/{{$post['id']}}">{{ $post['title'] }}</a></h3>
                        <p>{{ $post['excerpt'] }}</p>
                        <div class="d-flex align-items-center author">
                            <div class="photo"><img src="./assets/client/assets/img/person-2.jpg" alt="" class="img-fluid"></div>
                            <div class="name">
                                <h3 class="m-0 p-0">Wade Warren</h3>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
    
                <div class="row">
                   
                    <div class="col-lg-4">
                        <div class="post-entry-1 border-bottom">
                            <a href="/post/{{$postsByCategory2[$category['id']]['id']}}"><img src="{{$postsByCategory2[$category['id']]['image']}}" alt="" class="img-fluid"></a>
                            <div class="post-meta"><span class="date">{{ $category['name'] }}</span> <span class="mx-1">&bullet;</span> <span>Jul 5th '22</span></div>
                            <h2 class="mb-2"><a href="/post/{{$postsByCategory2[$category['id']]['id']}}"> {{ $postsByCategory2[$category['id']]['title'] }}</a></h2>
                            {{-- <span class="author mb-3 d-block">Jenny Wilson</span>
                            <p class="mb-4 d-block">Lorem ipsum dolor sit, amet consectetur adipisicing elit. Vero temporibus repudiandae, inventore pariatur numquam cumque possimus</p> --}}
                        </div>


                        
                        {{-- @include('components.post-entry1', [
                            'post' =>  $postFirstLatest,
                            'anh' => false,
                            
                            ]) --}}


                        
                    </div>                      
                    
                    <div class="col-lg-8">
                        <div class="post-entry-1">
                            <a href="/post/{{ $postFirstLatestTitle[$category['id']]['p_id']}}"><img src="{{ $postFirstLatestTitle[$category['id']]['p_image']}}" alt="" class="img-fluid"></a>
                            <div class="post-meta"><span class="date">{{ $category['p_name'] }}</span> <span class="mx-1">&bullet;</span> <span>Jul 5th '22</span></div>
                            <h2 class="mb-2"><a href="/post/{{ $postFirstLatestTitle2[$category['id']]['p_id']}}">{{ $postFirstLatestTitle[$category['id']]['p_title']}}</a></h2>
                            <span class="author mb-3 d-block">Jenny Wilson</span>
                            <p class="mb-4 d-block">{{ $postFirstLatestTitle2[$category['id']]['p_excertp']}}</p>
                        </div>
                    </div>
                </div>
            </div>
    
            <div class="col-md-3">

                @include('components.post-entry1',[
                    'posts' => $poststitileTradding[$category['id']],
                    'anh' => false,
                    'hiddenAuthor' => false, 
                     'hiddenExcerpt' => false
                ])


            </div>
        </div>
    
    </div>
    </section>
@endforeach
