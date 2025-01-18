 <!-- ======= Footer ======= -->
 @php $current_route_name=Route::currentRouteName() @endphp
 @php
 use App\Models\ContactSetting;
 $ContactSetting = ContactSetting::get_contact_us_details();
 use App\Models\SiteSetting;
 $social_facebook_url = SiteSetting::getSiteSettings('social_facebook_url');
 $social_linkedin_url = SiteSetting::getSiteSettings('social_linkedin_url');
 $social_instagram_url = SiteSetting::getSiteSettings('social_instagram_url');
 $social_youtube_url = SiteSetting::getSiteSettings('social_youtube_url');
 $social_twitter_url = SiteSetting::getSiteSettings('social_twitter_url');
 $footerLogo = SiteSetting::getSiteSettings('footer_logo');
 @endphp


<!-- info section -->
<section class="info_section ">
    <div class="container">
        <div class="row">
            <div class="col-md-3">
                <div class="info_contact">
                    <h5>
                        About Apartment
                    </h5>
                    <div>
                        <div class="img-box">
                            <img src="{{ asset('assets/front/images/location.png') }}" width="18px" alt="">
                        </div>
                        <p>
                            Address
                        </p>
                    </div>
                    <div>
                        <div class="img-box">
                            <img src="{{ asset('assets/front/images/phone.png') }}" width="12px" alt="">
                        </div>
                        <p>
                            +01 1234567890
                        </p>
                    </div>
                    <div>
                        <div class="img-box">
                            <img src="{{ asset('assets/front/images/mail.png') }}" width="18px" alt="">
                        </div>
                        <p>
                            demo@gmail.com
                        </p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info_info">
                    <h5>
                        Information
                    </h5>
                    <p>
                        ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="info_links">
                    <h5>
                        Useful Link
                    </h5>
                    <ul>
                        <li>
                            <a href="">
                                There are many
                            </a>
                        </li>
                        <li>
                            <a href="">
                                variations of
                            </a>
                        </li>
                        <li>
                            <a href="">
                                passages of
                            </a>
                        </li>
                        <li>
                            <a href="">
                                Lorem Ipsum
                            </a>
                        </li>
                        <li>
                            <a href="">
                                available, but
                            </a>
                        </li>
                        <li>
                            <a href="">
                                the i
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-3">
                <div class="info_form ">
                    <h5>
                        Newsletter
                    </h5>
                    <form action="">
                        <input type="email" placeholder="Enter your email">
                        <button>
                            Subscribe
                        </button>
                    </form>
                    <div class="social_box">
                        <a href="">
                            <img src="{{ asset('assets/front/images/fb.png') }}" alt="">
                        </a>
                        <a href="">
                            <img src="{{ asset('assets/front/images/twitter.png') }}" alt="">
                        </a>
                        <a href="">
                            <img src="{{ asset('assets/front/images/linkedin.png') }}" alt="">
                        </a>
                        <a href="">
                            <img src="{{ asset('assets/front/images/youtube.png') }}" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- end info_section -->
 <section class="container-fluid footer_section ">
     <div class="container">
         <p>
             &copy; <span id="displayYear"></span> All Rights Reserved By
             <a href="https://html.design/">Free Html Templates</a>
         </p>
     </div>
 </section>
