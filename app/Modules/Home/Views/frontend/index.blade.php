@extends('frontend.layouts.app')
@section('content')
    <!-- main slider start -->
    @if($sliders->count() > 0)
    <section class="bg-light position-relative">
        <div class="main-slider slick-dots-style slick-dots-align-center">
            @foreach($sliders as $slider)
                <div class="slider-item bg-img" style="background-image: url({{ url('/uploads/setting/slider/'.$slider->image) }})">
                    <div class="container">
                        <div class="row align-items-center slider-height slide2">
                            <div class="col-12">
                                <div class="slider-content">
                                    <h2 class="sub-title animated" data-animation-in="fadeInLeft" data-delay-in="1">
                                        {{ $slider->title }}
                                    </h2>
                                    {{--                                    <a href="#" class="btn btn-primary rounded animated mt-5rem" data-animation-in="zoomIn" data-delay-in="4">Shop Now</a>--}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!-- slick-progress -->
        <div class="slick-progress">
            <span></span>
        </div>
        <!-- slick-progress end-->
    </section>
    @endif
    <!-- main slider end -->

    <!-- product tab repetation start -->
    <section class="product-tab mb-5 mt-6rem bg-white pb-6rem">
        <div class="container">
            <div class="product-tab-nav border-bottom cbb1 mb-3rem">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title pb-4 pb-md-4 position-relative">
                            <h2 class="title">Our products</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- product-tab-nav end -->
            <div class="row" id="post-data">
                @include("Home::frontend.load-more")
            </div>
            <div class="ajax-load text-center" style="display: none">
                <p><img src="{{ url('/assets/frontend/loader/126.gif') }}" /></p>
            </div>
        </div>
    </section>
    <!-- product tab repetation end -->

    <!-- product tab repetation start -->
    <section class="product-tab bg-white pb-6rem">
        <div class="container">
            <div class="product-tab-nav border-bottom cbb1 mb-3rem">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title pb-4 pb-md-4 position-relative">
                            <h2 class="title">Top Viewed Products</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- product-tab-nav end -->
            @if($topProducts->count() > 0)
            <div class="row">
                <div class="col-12">
                    <div class="product-slider-init style">
                        @foreach($topProducts as $topProduct)
                        <div class="slider-item">
                            <div class="single-product position-relative">
                                {!! Form::hidden('quantity',1,['class'=>'qty-input']) !!}
                                @if($topProduct->discount)<span class="badge badge-danger cb3">-{{ $topProduct->discount }}%</span>@endif
                                <div class="product-thumbnail position-relative">
                                    <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($topProduct->id)) }}">
                                        @if($topProduct->photo_path)
                                        <img src="{{ url($topProduct->photo_path) }}" alt="{{ $topProduct->name }}" height="255" width="100%">
                                        @else
                                        <img src="{{ url('/assets/frontend/img/not-found.png') }}" alt="{{ $topProduct->name }}" height="255" width="100%">
                                        @endif
                                    </a>
                                    <!-- product links -->
                                    <ul class="product-links style2 flex-column d-flex justify-content-center">
                                        <li>
                                            @if(auth()->user())
                                                <a class="wishlist pointer" data-item="{{ $topProduct->id }}">
                                                    <span> <i class="ion-ios-heart-outline"></i> </span>
                                                </a>
                                            @else
                                                <a href="{{ url('/login') }}">
                                                    <span> <i class="ion-ios-heart-outline"></i> </span>
                                                </a>
                                            @endif
                                        </li>
                                        <li>
                                            <a class="quick-view" style="cursor: pointer" data-item="{{ $topProduct->id }}" data-toggle="modal" data-target="#quick-view">
                                                <span data-toggle="tooltip" data-placement="bottom" title="Quick view" data-original-title="Quick view">
                                                    <i class="ion-ios-search-strong"></i>
                                                </span>
                                            </a>
                                        </li>
                                        <li>
                                            <a style="cursor: pointer" class="add-to-cart" data-url="{{ $topProduct->id }}" data-toggle="modal" data-target="#right_modal_sm">
                                                <span data-toggle="tooltip" data-placement="bottom" title="Add To Cart">
                                                    <i class="ion-bag"></i>
                                                </span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class="product-desc pt-2rem position-relative text-center">
                                    <h3 class="title">
                                        <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($topProduct->id)) }}">{{ $topProduct->name }}</a>
                                    </h3>

                                    @if($topProduct->discount) <h6 class="product-price"><del>৳{{ $topProduct->price }}</del> <span class="text-danger ml-1">৳{{ $topProduct->price - ($topProduct->discount / 100)*$topProduct->price }}</span></h6> @endif
                                    @if(!$topProduct->discount) <h6 class="product-price">৳{{ $topProduct->price }}</h6> @endif

                                    <button class="pro-btn add-to-cart" data-url="{{ $topProduct->id }}" data-target="#right_modal_sm" data-toggle="modal">add to cart <i class="ion-bag"></i></button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    <!-- product tab repetation end -->
@endsection



