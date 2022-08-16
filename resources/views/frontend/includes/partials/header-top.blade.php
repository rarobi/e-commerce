<!-- header top start -->
<div class="header-top border-bottom ht-nav-br-bottom bg-light py-10 d-none d-lg-block">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-4">
                <div class="static-info">
                    <p class="text-dark">Welcome you to {{ session()->get('company.company_name') }}!</p>
                </div>
            </div>
            <div class="col-lg-8">
                <nav class="header-top-nav">
                    <ul class="d-flex justify-content-end align-items-center">
                        <li>
                            <a href="{{ url('/wishlist') }}">
                                <i class="ion-android-favorite-outline"></i> Wishlist <span class="wishlist-item">@if(auth()->user()) {{ '('. \App\Libraries\CommonFunction::authCustomerWishlistItem() .')' }} @else {{ '(0)' }} @endif</span></a>
                            <span class="separator">|</span>
                        </li>
                        <li>
                            <a href="{{ url('/cart') }}">
                                <i class="ion-bag"></i> Cart <span class="total-item">@if(session()->has('totalItem')) {{ '('.session()->get('totalItem').')' }} @else {{ '(0)' }} @endif</span></a>
                            <span class="separator">|</span>
                        </li>
                        <li>
                            <a href="#" role="button" id="dropdown1" data-toggle="dropdown" aria-haspopup="true"
                               aria-expanded="false"><i class="ion icon-user"></i> Account <i class="ion ion-ios-arrow-down"></i></a>
                            <ul class="topnav-submenu dropdown-menu" aria-labelledby="dropdown1">
                                @if(!auth()->user())
                                <li><a href="{{ url('/login') }}">Sign in</a></li>
                                @else
                                <li>
                                    @if(auth()->user()->user_type == '1x101')<a href="{{ url('/admin/dashboard') }}">Dashboard</a> @else <a href="{{ url('/dashboard') }}">Dashboard</a> @endif
                                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"> Sign out
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
<!-- header top end -->
