@if (isset($posts))
    @foreach ($posts as $post)

    <div class="post-entry-1 lg">
        @if (!isset($anh))
        <a href="/post/{{$post['p_id']}}"><img src="{{ $post['p_image']}}" alt="" class="img-fluid"></a>
        @endif
        <div class="post-meta"><span class="date">{{ $post['c_name'] }}</span> <span class="mx-1">&bullet;</span> <span>Jul 5th '22</span></div>
        <h4><a href="/post/{{$post['p_id']}}">{{$post['p_title'] }}</a></h4>
    
        @if(!isset($hiddenExcerpt) )
        <p class="mb-4 d-block">{{$post['p_excerpt'] }}</p>
        @endif
    
        @if(!isset($hiddenAuthor) )
    
        <div class="d-flex align-items-center author">
            <div class="photo"><img src="./assets/client/assets/img/person-1.jpg" alt="" class="img-fluid"></div>
            <div class="name">
                <h3 class="m-0 p-0">Cameron Williamson</h3>
            </div>
        </div>
    
        @endif
    </div>   
    @endforeach
@else
<div class="post-entry-1 lg">
    @if (!isset($anh))
    <a href="/post/{{$post['p_id']}}"><img src="{{ $post['p_image']}}" alt="" class="img-fluid"></a>
    @endif
    <div class="post-meta"><span class="date">{{ $post['c_name'] }}</span> <span class="mx-1">&bullet;</span> <span>Jul 5th '22</span></div>
    <h4><a href="/post/{{$post['p_id']}}">{{$post['p_title'] }}</a></h4>

    @if(!isset($hiddenExcerpt) )
    <p class="mb-4 d-block">{{$post['p_excerpt'] }}</p>
    @endif

    @if(!isset($hiddenAuthor) )

    <div class="d-flex align-items-center author">
        <div class="photo"><img src="./assets/client/assets/img/person-1.jpg" alt="" class="img-fluid"></div>
        <div class="name">
            <h3 class="m-0 p-0">Cameron Williamson</h3>
        </div>
    </div>

    @endif
</div>
@endif