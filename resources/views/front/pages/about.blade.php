@extends('front.layouts.main')
@section('title', 'About')
@section('css')

@stop
@section('content')

<section class="about_section layout_padding-bottom">
    <div class="square-box">
      <img src="{{ asset('assets/front/images/square.png') }}" alt="">
    </div>
    <div class="container">
      <div class="row">
        <div class="col-md-6">
          <div class="img-box">
            <img src="{{ asset('assets/front/images/about-img.jpg') }}" alt="">
          </div>
        </div>
        <div class="col-md-6">
          <div class="detail-box">
            <div class="heading_container">
              <h2>
                About Our Apartment
              </h2>
            </div>
            <p>
              There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration
              in
              some form, by injected humour, or randomised words
            </p>
            <a href="">
              Read More
            </a>
          </div>
        </div>
      </div>
    </div>
  </section>
@stop
