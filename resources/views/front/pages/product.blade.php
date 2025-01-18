@php
use App\Models\SiteSetting;
$contact_enquiry_phone = SiteSetting::getSiteSettings('contact_enquiry_phone');
 @endphp

@extends('front.layouts.main')
@section('title', 'Product Details')

@section('css')
<style>
    p {
        margin: 0;
    }
</style>
@stop
@section('content')
<!-- blog section start -->
<div class="blog_section layout_padding">
    <div class="container my-5">
        <div class="row">
            <div class="col-md-6">
                <div class="blog_img"><img width="500px" height="300px" src="{{ $Product->image ? asset($Product->image) : asset('assets/front/images/blog-img.png') }}"></div>
            </div>
            <div class="col-md-6">
                <h1 class="blog_taital">{{$Product->name}}</h1>
                <p class="blog_text">{!! $Product->description !!}</p>
                <h3 class="mt-3">Price : {{$Product->price}}</h3>
                @if (isset($contact_enquiry_phone) &&
                                     isset($contact_enquiry_phone->value) &&
                                     $contact_enquiry_phone != null &&
                                     $contact_enquiry_phone->value != '')
                                     <a class="text-primary" href="tel:{{ $contact_enquiry_phone->value ? $contact_enquiry_phone->value : '' }}"><h2 class="text-primary">For More Details Call Us : {{$contact_enquiry_phone->value}}</h2></a>
                                     @endif
                <!-- <div class="read_bt"><a href="#">Read More</a></div> -->
            </div>
        </div>
    </div>
</div>
<!-- blog section end -->
@stop
