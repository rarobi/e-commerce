@extends('frontend.layouts.app')
@section('content')
    <!-- product tab start -->
    <nav class="breadcrumb-section bg-white pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Products</li>
                    </ol>
                </div>
            </div>
        </div>
    </nav>
    <!-- bread crumb end -->

    <!-- product tab repetation start -->
    <section class="product-tab mb-5 mt-6rem bg-white pb-6rem">
        <div class="container">
            <!-- product-tab-nav end -->
            <div class="row" id="post-data">
                @include("Product::frontend.product.load-more")
            </div>
            <div class="ajax-load text-center" style="display: none">
                <p><img src="{{ url('/assets/frontend/loader/126.gif') }}" /></p>
            </div>
        </div>
    </section>
    <!-- product tab repetation end -->
@endsection



