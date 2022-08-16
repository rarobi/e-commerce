@extends('frontend.layouts.app')
@section('title') Product Details @endsection
@section('content')
    <!-- product tab start -->
    <nav class="breadcrumb-section bg-white pt-5 pb-6rem">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/categories/'.\App\Libraries\Encryption::encodeId($product->product_category_id)) }}">{{ $product->product_category_name }}</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ url('/subcategories/'.\App\Libraries\Encryption::encodeId($product->product_subcategory_id)) }}">{{ $product->product_subcategory_name }}</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $product->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </nav>
    <!-- bread crumb end -->

    <!-- single-product start -->
    <section class="product-single style1 pb-6rem">
        <div class="container">
            <div class="row">
                @if($productPhotos->count() > 0)
                <div class="col-md-6 mx-auto col-lg-5 mb-5 mb-lg-0">
                    <div class="product-sync-init mb-20">
                        @foreach($productPhotos as $productPhoto)
                        <div class="single-product">
                            <div class="product-thumb">
                                <img src="{{ !$productPhoto->path ? url('/assets/frontend/img/not-found.png') : url($productPhoto->path) }}" alt="{{ $product->name }}" height="445" width="100%">
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <div class="product-sync-nav">
                        @foreach($productPhotos as $productPhoto)
                        <div class="single-product mt-2">
                            <div class="product-thumb">
                                <a href="javascript:void(0)">
                                    <img src="{{ !$productPhoto->path ? url('/assets/frontend/img/not-found.png') : url($productPhoto->path) }}" alt="{{ $product->name }}" height="88" width="100%">
                                </a>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
                @else
                    <div class="col-md-6 mx-auto col-lg-5 mb-5 mb-lg-0">
                        <div class="product-sync-init mb-20">
                            <div class="single-product">
                                <div class="product-thumb">
                                    <img src="{{ url('/assets/frontend/img/not-found.png') }}" alt="{{ $product->name }}" height="445" width="100%">
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-lg-6 mt-5 mt-md-0">
                    <div class="modal-product-info">
                        <div class="product-head">
                            <h2 class="title">{{ $product->name }}</h2>
                            <h4 class="sub-title">Category : {{ $product->product_category_name }}</h4>
                            Brand : <a href="//{{ $product->website }}" target="_blank"><span class="edite"> {{ $product->product_brand_name }} </span> <img class="ml-2" src="{{ url('/uploads/brand-photos/'.$product->brand_photo) }}" alt="{{ $product->product_brand_name }}" height="60" width="60" title="{{ $product->product_brand_name }}"></a>
                            <div class="star-content">
                                <span class="star-on"><i class="fas fa-star"></i> </span>
                                <span class="star-on"><i class="fas fa-star"></i> </span>
                                <span class="star-on"><i class="fas fa-star"></i> </span>
                                <span class="star-on"><i class="fas fa-star"></i> </span>
                                <span class="star-on"><i class="fas fa-star"></i> </span>
{{--                                <a href="#" id="write-comment"><span class="ml-2"><i class="far fa-comment-dots"></i></span> Read reviews <span>(1)</span></a>--}}
{{--                                <a href="{{ url('') }}" data-toggle="modal" data-target="#exampleModalCenter"><span class="edite"><i class="far fa-edit"></i></span> Write a review</a>--}}
                                <div class="product-discount">
                                    @if($product->discount)
                                    <span class="regular-price"> <del>৳{{ $product->price }}</del> ৳{{ $product->price - ($product->discount / 100) * $product->price }}</span>
                                    <span class="badge badge-dark">Save {{ $product->discount }}%</span>
                                    @else
                                        <span class="regular-price">৳{{ $product->price }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="product-body">
                            <p>{{ $product->short_description }}</p>
                        </div>

                        <div class="product-footer single-product">
                            <div class="product-count style d-flex flex-column flex-sm-row mt-5 mb-5 pt-3">
                                <div class="count d-flex">
                                    <input class="qty-input" type="number" min="1" max="10" step="1" value="1">
                                    <div class="button-group">
                                        <button class="count-btn increment"><i class="fas fa-chevron-up"></i></button>
                                        <button class="count-btn decrement"><i class="fas fa-chevron-down"></i></button>
                                    </div>
                                </div>
                                <div>
                                    <button class="btn btn-primary rounded mt-5 mt-sm-0 add-to-cart" data-url="{{ $product->id }}" data-toggle="modal" data-target="#right_modal_sm">
                                        <span class="mr-2"><i class="ion-android-add"></i></span>
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- single-product end -->


    <div class="product-tab bg-light py-6rem">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 rounded-0">
                        <div class="card-body">
                            <nav class="product-tab-menu style1 border-bottom cbb1 mb-4rem pr-0">
                                <ul class="nav nav-pills justify-content-center" id="pills-tab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="pills-product-description-tab" data-toggle="pill" href="#pills-product-description" role="tab" aria-controls="pills-product-description" aria-selected="true">Description</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-product-detail-tab" data-toggle="pill" href="#pills-product-detail" role="tab" aria-controls="pills-product-detail" aria-selected="false">Product Details</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="pills-product-review-tab" data-toggle="pill" href="#pills-product-review" role="tab" aria-controls="pills-product-review" aria-selected="false">Reviews</a>
                                    </li>
                                </ul>
                            </nav>
                            <div class="tab-content" id="pills-tabContent">
                                <!-- first tab-pane -->
                                <div class="tab-pane fade show active" id="pills-product-description" role="tabpanel" aria-labelledby="pills-product-description-tab">
                                    <div class="product-description">
                                        <p class="text-left">{{ $product->short_description }}</p>
                                    </div>
                                </div>
                                <!-- second tab-pane -->
                                <div class="tab-pane fade" id="pills-product-detail" role="tabpanel"
                                     aria-labelledby="pills-product-detail-tab">
                                    <div class="studio-thumb">
                                        <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($product->id)) }}"><img src="{{ !$product->photo_path ? url('/assets/frontend/img/not-found.png') : url($product->photo_path) }}" alt="{{ $product->name }}" height="88" width="189" title="{{ $product->name }}"></a>
                                        <h3>Data sheet</h3>
                                    </div>
                                    <div class="product-features">
                                        <ul>
                                            <li><span>Category</span></li>
                                            <li><span>{{ ($product->product_category_name) ? $product->product_category_name : '-' }}</span></li>
                                            <li><span>Subcategory</span></li>
                                            <li><span>{{ ($product->product_subcategory_name) ? $product->product_subcategory_name : '-' }}</span></li>
                                            <li><span>Brand</span></li>
                                            <li><span>{{ ($product->product_brand_name) ? $product->product_brand_name : '-' }}</span></li>
                                            <li><span>Sku</span></li>
                                            <li><span>{{ ($product->product_sku_name) ? $product->product_sku_name : '-' }}</span></li>
                                            <li><span>Color</span></li>
                                            <li><span>{{ ($product->color) ? $product->color : '-' }}</span></li>
                                            <li><span>Size</span></li>
                                            <li><span>{{ ($product->size) ? $product->size : '-' }}</span></li>
                                            <li><span>Weight</span></li>
                                            <li><span>{{ ($product->weight) ? $product->weight : '-' }}</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <!-- third tab-pane -->
                                <div class="tab-pane fade" id="pills-product-review" role="tabpanel"
                                     aria-labelledby="pills-product-review-tab">
                                    <div class="grade-content">
                                        <span class="grade">Grade </span>
                                        <span class="star-on"><i class="fas fa-star"></i> </span>
                                        <span class="star-on"><i class="fas fa-star"></i> </span>
                                        <span class="star-on"><i class="fas fa-star"></i> </span>
                                        <span class="star-on"><i class="fas fa-star"></i> </span>
                                        <span class="star-on"><i class="fas fa-star"></i> </span>
                                        <h6 class="sub-title">Hastheme</h6>
                                        <p>24/08/2020</p>
                                        <h4 class="title">demo</h4>
                                        <p>ok !</p>
                                        <a href="#" class="btn btn-dark3 mt-15" data-toggle="modal" data-target="#exampleModalCenter">Write your review !</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- product tab end -->
@endsection
