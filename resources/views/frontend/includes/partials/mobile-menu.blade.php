<!-- offcanvas-mobile-menu start -->
<div id="offcanvas-mobile-menu" class="offcanvas offcanvas-mobile-menu">
    <div class="border-bottom mb-4 pb-4 text-right">
        <button class="offcanvas-close">×</button>
    </div>
    <div class="offcanvas-head mb-4 pb-2">
        <div class="static-info py-3 px-2 text-center">
            <p class="text-dark">Welcome you to {{ session()->get('company.company_name') }}!</p>
        </div>
        <nav class="offcanvas-top-nav">
            <ul class="d-flex justify-content-center align-items-center">
                <li class="mx-4">
                    <a href="{{ url('/wishlist') }}"><i class="ion-ios-loop-strong"></i> Wishlist <span class="wishlist-item">@if(auth()->user()) {{ '('. \App\Libraries\CommonFunction::authCustomerWishlistItem() .')' }} @else {{ '(0)' }} @endif</span></a>
                </li>
                <li class="mx-4">
                    <a href="{{ url('/cart') }}"> <i class="ion-bag"></i> Cart <span class="total-item">@if(session()->has('totalItem')) {{ '('.session()->get('totalItem').')' }} @else {{ '0' }} @endif</span></a>
                </li>
            </ul>
        </nav>
    </div>
    <nav class="offcanvas-menu">
        <ul>
            <li><a href="{{ url('/') }}">Home</a></li>
            <li><a href="{{ url('/about-us') }}">About Us</a></li>
            <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
            <li><a href="{{ url('/blogs') }}">Blog</a></li>
        </ul>
    </nav>
    <div class="offcanvas-social mt-30">
        <ul>
            <li>
                <a href="//{{ session()->get('company.facebook_link') }}"><i class="icon-social-facebook"></i></a>
            </li>
            <li>
                <a href="//{{ session()->get('company.twitter_link') }}"><i class="icon-social-twitter"></i></a>
            </li>
            <li>
                <a href="//{{ session()->get('company.youtube_link') }}"><i class="icon-social-instagram"></i></a>
            </li>
            <li>
                <a href="//{{ session()->get('company.google_plus_link') }}"><i class="icon-social-google"></i></a>
            </li>
            <li>
                <a href="//{{ session()->get('company.instagram_link') }}"><i class="icon-social-instagram"></i></a>
            </li>
        </ul>
    </div>
</div>
<!-- offcanvas-mobile-menu end -->
