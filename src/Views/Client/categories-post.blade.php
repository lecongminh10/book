@extends('layouts.master')

@section('title')
    {{ $post['p_title'] }}
@endsection

@section('content')
    <section class="single-post-content">
        <div class="container">
            <div class="row">
                <div class="col-md-9" data-aos="fade-up">
                    <h3 class="category-title">Category: {{ $title['name'] }}</h3>
                    @foreach ($posts as $post)
                    <div class="d-md-flex post-entry-2 half">
                        <a href="/post/{{$post['p_id']}}" class="me-4 thumbnail">
                          <img src="{{ $post['p_image'] }}" alt="" class="img-fluid">
                        </a>
                        <div>
                          <h3><a href="/post/{{$post['p_id']}}">{{$post['p_title']}}</a></h3>
                          <p>{{ $post['p_excerpt'] }}</p>
                          <div class="d-flex align-items-center author">
                            <div class="photo"><img src="../assets/client/assets/img/person-2.jpg" alt="" class="img-fluid"></div>
                            <div class="name">
                              <h3 class="m-0 p-0">Wade Warren</h3>
                            </div>
                          </div>
                        </div>
                      </div>
                    @endforeach

        
                    <div class="text-start py-4">
                      <div class="custom-pagination">
                        <a href="#" class="prev">Prevous</a>
                        <a href="#" class="active">1</a>
                        <a href="#">2</a>
                        <a href="#">3</a>
                        <a href="#">4</a>
                        <a href="#">5</a>
                        <a href="#" class="next">Next</a>
                      </div>
                    </div>
                  </div>
                
                  @include('components.sidebar')
               
              
            </div>
        </div>
    </section>
@endsection