<!-- header bottom start -->
<nav id="sticky" class="header-bottom bg-primary nav-style2 py-3 py-lg-0 bg-sm-transparent">
    <div class="container">
        <div class="row align-items-center d-none d-lg-flex">
            <div class="col-lg-3">
                <div class="vertical-menu">
                    <button class="menu-btn d-flex">
                        <span class="ion-android-menu"></span>All Categories<span class="ion-ios-arrow-down d-block ml-auto"></span>
                    </button>
                    @php $productsMenu = \App\Libraries\CommonFunction::productMenu(); @endphp
                    @if(count($productsMenu) > 0)
                    <ul class="vmenu-content display-none">
                        @foreach($productsMenu as $productCategoryId => $productMenu)
                        <li class="menu-item">
                            <a href="{{ url('/categories/'.\App\Libraries\Encryption::encodeId($productCategoryId)) }}">{{ $productMenu['category_name'] }} <i class="ion-ios-arrow-right"></i></a>
                            @if(isset($productMenu['product_subcategory']))
                            <ul class="verticale-mega-menu flex-wrap">
                                @foreach($productMenu['product_subcategory'] as $productSubcategoryId => $productSubcategory)
                                <li>
                                    <ul class="submenu-item">
                                        <li><a href="{{ url('/subcategories/'.\App\Libraries\Encryption::encodeId($productSubcategoryId)) }}">{{ $productSubcategory }}</a></li>
                                    </ul>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                            <!-- sub menu -->
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <!-- menu content -->
                </div>
            </div>

            <div class="col-lg-6">
                <div class="search-form-wrapper pl-lg-4">
                    <div class="search-form">
                        {!! Form::open(['url'=>'/search-products','method'=>'GET','class'=>'form-inline position-relative']) !!}
                        {!! Form::text('search_key','',['class'=>'form-control','placeholder'=>'Enter your search key ...']) !!}
                        <button style="border: 1px solid #ffffff" class="btn bg-primary search-btn" type="submit"><i class="ion-ios-search-strong"></i></button>
                        <span class="select-arrow-down"> <i class="ion-ios-arrow-down"></i> </span>
                        @if(count($productsMenu) > 0)
                        <div class="search-form-select">
                            <select class="select" name="category_id">
                                <option>All categories</option>
                                @foreach($productsMenu as $productCategoryId => $productMenu)
                                    <option class="text-left" value="{{ $productCategoryId }}">
                                        {{ $productMenu['category_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        {!! Form::close() !!}
                    </div>

                </div>
            </div>

            <div class="col-lg-3">

               <div class="row">
                   <div class="col-md-3 ml-auto">
                       <div class="cart-block d-inline-block position-relative">
                           <a href="{{ url('/wishlist') }}">
                                <span class="position-relative">
                                    <i class="ion-ios-heart-outline" style="font-size: 30px"></i>
                                    <span class="badge badge-light cb1 total-wishlist-item">@if(auth()->user()) {{ \App\Libraries\CommonFunction::authCustomerWishlistItem() }} @else {{ '0' }} @endif </span>
                                </span>
                           </a>
                       </div>
                   </div>
                   <div class="col-md-6">
                       <div class="cart-block d-inline-block position-relative">
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
                       <!-- cart block end -->
                   </div>
               </div>
            </div>
        </div>

        <div class="row align-items-center d-lg-none">
            <div class="col-lg-12">
                <div class="search-form-wrapper">
                    <div class="search-form">
                        {!! Form::open(['url'=>'search-products','method'=>'GET','class'=>'form-inline position-relative']) !!}
                            {!! Form::text('search_key','',['class'=>'form-control border-black','placeholder'=>'Enter your search key ...']) !!}
                            <button class="btn bg-dark search-btn" type="submit"><i class="ion-ios-search-strong"></i></button>
                            <span class="select-arrow-down"> <i class="ion-ios-arrow-down"></i> </span>
                            <div class="search-form-select">
                                @if(count($productsMenu) > 0)
                                    <div class="search-form-select">
                                        <select class="select" name="category_id">
                                            <option>All categories</option>
                                            @foreach($productsMenu as $productCategoryId => $productMenu)
                                                <option class="mr-1" value="{{ $productCategoryId }}">
                                                    {{ $productMenu['category_name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
                <!-- cart block end -->
            </div>
        </div>
    </div>
</nav>
<!-- header bottom end -->

