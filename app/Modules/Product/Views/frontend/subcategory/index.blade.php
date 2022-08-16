@extends('frontend.layouts.app')
@section('content')
    <!-- breadcrumb -->
    <nav class="breadcrumb-section bg-white pt-5">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $subcategory->name }}</li>
                    </ol>
                </div>
            </div>
        </div>
    </nav>
    <!-- bread crumb end -->

    <!-- product tab repetation start -->
    <section class="product-tab mb-5 mt-6rem bg-white pb-6rem">
        <div class="container">
            <div class="product-tab-nav border-bottom cbb1 mb-3rem">
                <div class="row">
                    <div class="col-12">
                        <div class="section-title pb-4 pb-md-4 position-relative">
                            <h2 class="title">{{ $subcategory->name }}</h2>
                        </div>
                    </div>
                </div>
            </div>
            <!-- product-tab-nav end -->
            <div class="row" id="post-data">
                @include("Product::frontend.subcategory.load-more")
            </div>
            <div class="ajax-load text-center" style="display: none">
                <p><img src="{{ url('/assets/frontend/loader/126.gif') }}" /></p>
            </div>
        </div>
    </section>
    <!-- product tab repetation end -->

@endsection



