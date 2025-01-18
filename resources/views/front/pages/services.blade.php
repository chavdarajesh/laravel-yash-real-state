@extends('front.layouts.main')
@section('title', 'Services')
@section('css')

@stop
@section('content')
<!-- services section start -->
<div class="services_section layout_padding">
    <div class="container">
        <h1 class="services_taital">What We Do</h1>
        <p class="about_text">It is a long established fact that a reader will be distracted by the readable content of a page when</p>
        @if (!$Services->isEmpty())
        <div class="about_section_2">
        @foreach ($Services as $key => $Service)
            <div class="row mb-3">
                <div class="col-lg-6">
                    <div class="about_image"><img src="{{ $Service->image ? asset($Service->image) : asset('assets/front/images/about-img.png')}}"></div>
                </div>
                <div class="col-lg-6">
                    <div class="mt-5">
                        <p class="lorem_text">{!!   substr($Service->description, 0, 250) !!}</p>
                        <div class="read_bt"><a data-toggle="collapse" href="#collapseExample-{{$key}}" role="button" aria-expanded="false" aria-controls="collapseExample-{{$key}}">Read More</a></div>

                        <div class="collapse lorem_text mt-3 " id="collapseExample-{{$key}}">
                            <div class="card card-body">
                            {!!   substr($Service->description,250) !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
        @endif
    </div>
</div>
<!-- services section end -->
@stop
