<!-- header-middle satrt -->
<div class="header-middle nav-style2 py-3rem">
    <div class="container">
        <div class="row align-items-center d-lg-none">
            <div class="col-4">
                <nav class="header-top-nav d-flex align-items-center">
                    <ul>
                        <li class="mr-4">
                            <a href="#" role="button" id="dropdown4" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ion-ios-contact"></i></a>
                            <ul class="topnav-submenu dropdown-menu" aria-labelledby="dropdown4">
                                <li><a href="{{ url('my-account') }}">My account</a></li>
                                <li><a href="{{ url('/checkout') }}">Checkout</a></li>
                                <li><a href="{{ url('logout') }}">Sign out</a></li>
                            </ul>
                        </li>
                    </ul>
                    <div class="cart-block position-relative" style="margin-top: 25px">
                        <a href="{{ url('/cart') }}">
                        <span class="position-relative">
                            <i class="ion-bag"></i>
                            <span class="badge badge-light cb1 basket-item-count totalItem">@if(session()->get('totalItem')) {{ session()->get('totalItem') }} @else 0 @endif </span>
                        </span>
                            <span class="totalAmount"> @if(session()->get('totalAmount')) {{ '৳'.session()->get('totalAmount') }} @else ৳0.00 @endif </span>
                        </a>
                        <div class="small-cart" style="max-height: calc(100vh - 200px); overflow-y: auto;">
                            @if(session()->get('cart'))
                                @php
                                    $subtotal = 0;
                                    $tax = 0;
                                @endphp
                                <div class="small-cart-item">
                                    @foreach(session()->get('cart') as $cartItemId => $cartItem)
                                        <div class="single-item">
                                            <div class="image">
                                                <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($cartItemId)) }}">
                                                    <img src="{{ !$cartItem['photo'] ? url('/assets/frontend/img/not-found.png') : url($cartItem['photo']) }}" alt="{{ $cartItem['name'] }}" height="70" width="70">
                                                </a>
                                                <span class="badge badge-primary cb2">{{$cartItem['quantity']}}x</span>
                                            </div>

                                            @php
                                                $price = ($cartItem['discount']) ? ($cartItem['price'] - ($cartItem['price']) * ($cartItem['discount'] / 100)) : $cartItem['price'];
                                                $quantityWisePrice = $cartItem['quantity'] * $price;
                                            @endphp

                                            <div class="cart-content">
                                                <p class="cart-name"><a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($cartItemId)) }}">{{ $cartItem['name'] }}</a></p>
                                                <p class="cart-quantity">৳{{ $price }}</p>
                                                <p class="cart-color">{{ $cartItem['quantity'] }} x ৳{{ $price }} <span> = ৳ {{ $quantityWisePrice }}</span></p>
                                            </div>
                                            <a class="remove-icon remove-item" style="cursor: pointer" data-item="{{ $cartItemId }}"><i class="ion-close-round"></i></a>
                                        </div>
                                        @php
                                            $subtotal += $quantityWisePrice;
                                            $tax += $cartItem['tax'];
                                        @endphp
                                    @endforeach
                                </div>
                                <div class="cart-table">
                                    <table class="table m-0">
                                        <tbody>
                                        <tr>
                                            <td class="text-left">Subtotal:</td>
                                            <td class="text-right"><span>৳{{ $subtotal }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Taxes:</td>
                                            <td class="text-right"><span>৳{{ $tax }}</span></td>
                                        </tr>
                                        <tr>
                                            <td class="text-left">Total:</td>
                                            <td class="text-right"><span>৳{{ $subtotal + $tax }}</span></td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-4 text-center">
                <div class="logo mt-3 mb-2rem">
                    <a href="{{ url('/') }}">
                        @if(session()->get('company.company_logo'))
                        <img src="{{ url('/uploads/appearance/'.session()->get('company.company_logo')) }}" alt="{{ session()->get('company.company_name') }}" height="22.14" width="100%">
                        @else
                        <img src="{{ url('/assets/frontend/img/not-found.png') }}" alt="Company" height="22.14" width="100%">
                        @endif
                    </a>
                </div>
            </div>
            <!-- mobile-menu-toggle start -->
            <div class="col-4 text-right">
                <div class="mobile-menu-toggle">
                    <a href="#offcanvas-mobile-menu" class="offcanvas-toggle">
                        <svg viewBox="0 0 800 600">
                            <path d="M300,220 C300,220 520,220 540,220 C740,220 640,540 520,420 C440,340 300,200 300,200" id="top"></path>
                            <path d="M300,320 L540,320" id="middle"></path>
                            <path d="M300,210 C300,210 520,210 540,210 C740,210 640,530 520,410 C440,330 300,190 300,190" id="bottom" transform="translate(480, 320) scale(1, -1) translate(-480, -318) "></path>
                        </svg>
                    </a>
                </div>
            </div>
            <!-- mobile-menu-toggle end -->
        </div>
        <div class="row align-items-center position-relative d-none d-lg-flex">
            <div class="col-lg-3 d-none d-lg-block">
                <div class="logo">
                    <a href="{{ url('/') }}">
                        @if(session()->get('company.company_logo'))
                        <img src="{{ url('/uploads/appearance/'.session()->get('company.company_logo')) }}" alt="{{ session()->get('company.company_name') }}" height="52" width="100%">
                        @else
                        <img src="{{ url('/assets/frontend/img/not-found.png') }}" alt="Company" height="52" width="100%">
                        @endif
                    </a>
                </div>
            </div>
            <div class="col-lg-6 position-static">
                <ul class="main-menu d-flex">
                    <li><a href="{{ url('/') }}">Home</a></li>
                    <li><a href="{{ url('/about-us') }}">About Us</a></li>
                    <li><a href="{{ url('/contact-us') }}">Contact Us</a></li>
                    <li><a href="{{ url('/blogs') }}">Blog</a></li>
                </ul>
            </div>
            <div class="col-lg-3 text-right">
                <!-- search-form end -->
                <div class="media static-media d-none d-lg-inline-flex">
                    <img class="mr-3" src="/assets/frontend/img/icon/6.png" alt="icon">
                    <div class="media-body">
                        <div class="phone">
                            <strong class="text-dark">Call us:</strong>
                            <a href="tel:{{ session()->get('company.company_phone') }}" class="text-primary">{{ session()->get('company.company_phone') }}</a>
                        </div>
                        <div class="email">
                            <a href="mailto:{{ session()->get('company.company_email') }}" class="text-dark">{{ session()->get('company.company_email') }}</a>
                        </div>
                    </div>
                </div>
                <!-- static-media end -->
            </div>
        </div>
    </div>
</div>
<!-- header-middle end -->
