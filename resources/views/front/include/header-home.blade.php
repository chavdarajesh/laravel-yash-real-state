@php $current_route_name=Route::currentRouteName();
use App\Models\SiteSetting;
$headerLogo = SiteSetting::getSiteSettings('header_logo');
$loader = SiteSetting::getSiteSettings('loader');
@endphp

<div class="hero_area">
    <!-- header section strats -->
    <header class="header_section">
        <div class="container-fluid">
            <nav class="navbar navbar-expand-lg custom_nav-container">
                <a class="navbar-brand" href="{{ route('front.home') }}">
                    <img src="images/logo.png" alt="" />
                </a>
                <div class="navbar-collapse" id="">
                    <ul class="navbar-nav justify-content-between ">
                        <div class="User_option">
                            <li class="">
                                <a class="mr-4" href="">
                                    Login
                                </a>
                                <a class="" href="">
                                    Sign up
                                </a>
                            </li>
                        </div>
                    </ul>

                    <div class="custom_menu-btn">
                        <button onclick="openNav()">
                            <span class="s-1">

                            </span>
                            <span class="s-2">

                            </span>
                            <span class="s-3">

                            </span>
                        </button>
                    </div>
                    <div id="myNav" class="overlay">
                        <div class="overlay-content">
                            <a href="{{ route('front.home') }}">HOME</a>
                            <a href="{{ route('front.about') }}">ABOUT</a>
                            <a href="house.html">HOUSE</a>
                            <a href="price.html">PRICING</a>
                            <a href="contact.html">CONTACT US</a>
                        </div>
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- end header section -->
    <!-- slider section -->
    <section class="slider_section ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-4 offset-md-1">
                    <div class="detail-box">
                        <h1>
                            <span> Modern</span> <br>
                            Apartment <br>
                            House
                        </h1>
                        <p>
                            It is a long established fact that a reader will be distracted by the readable content of
                        </p>
                        <div class="btn-box">
                            <a href="" class="">
                                Read More
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end slider section -->
</div>
