@extends('frontend.layouts.app')
@section('content')

    <!-- bread crumb start -->
    <nav class="breadcrumb-section bg-white pt-5 pb-6rem">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="{{ config('misc.app_url') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">cart</li>
                    </ol>
                </div>
            </div>
        </div>
    </nav>
    <!-- bread crumb end -->
    <!--cart-section satrt -->
    <section class="whish-list-section pb-6rem">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="title text-capitalize">Your cart items</h3>
                </div>
            </div>
                <div class="row updateCart">
                    @if(session()->get('cart'))
                        <div class="col-md-8">
                            <div class="table-responsive pt-4">
                                <table class="table border-bottom">
                                    <thead class="thead-light">
                                    <tr>
                                        <th class="text-center" scope="col">Product Image</th>
                                        <th class="text-center" scope="col">Product Name</th>
                                        <th class="text-center" scope="col">Price</th>
                                        <th class="text-center" scope="col">Qty</th>
                                        <th class="text-center" scope="col"></th>
                                        <th class="text-center" scope="col">Total</th>
                                        <th class="text-center" scope="col">action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php
                                        $subtotal = 0;
                                        $tax = 0;
                                        $quantity = 0;
                                        $shippingFee = 60;
                                    @endphp
                                    @foreach(session()->get('cart') as $cartItemId => $cartItem)
                                        <tr>
                                            <input class="product-item" type="hidden" value="{{ $cartItemId }}">
                                            <th class="text-center" scope="row">
                                                <img src="{{ !$cartItem['photo'] ? url('/assets/frontend/img/not-found.png') : url($cartItem['photo']) }}" alt="{{ $cartItem['name'] }}" height="80" width="90">
                                            </th>
                                            <td class="text-center">
                                                <span class="whish-title">{{ $cartItem['name'] }}</span>
                                            </td>

                                            @php
                                                $price = ($cartItem['discount']) ? ($cartItem['price'] - ($cartItem['price']) * ($cartItem['discount'] / 100)) : $cartItem['price'];
                                                $quantityWisePrice = $cartItem['quantity'] * $price;
                                                $quantity+= $cartItem['quantity'];
                                            @endphp

                                            <td class="text-center">
                                                <span class="whish-title">৳{{ $price }}</span>
                                            </td>

                                            <td class="text-center">
                                                <div class="product-count style">
                                                    <div class="count d-flex justify-content-center">
                                                        <input class="qty-input" type="number" min="1" max="10" step="1" value="{{$cartItem['quantity']}}">
                                                        <div class="button-group">
                                                            <button class="count-btn increment"><i class="fas fa-chevron-up"></i></button>
                                                            <button class="count-btn decrement"><i class="fas fa-chevron-down"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <button class="btn btn-primary rounded mt-5 mt-sm-0 update-item"> Update</button>
                                            </td>
                                            <td class="text-center">
                                                <span class="product-price"> ৳{{ $quantityWisePrice }} </span>
                                            </td>
                                            <td class="text-center">
                                                <a class="remove-item" style="cursor: pointer" data-item="{{ $cartItemId }}"> <span class="trash"><i class="fas fa-trash-alt"></i> </span></a>
                                            </td>
                                        </tr>
                                        @php
                                            $subtotal += $quantityWisePrice;
                                            $tax += $cartItem['tax'];
                                        @endphp
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-lg-4 mt-4">
                            <ul class="list-group cart-summary rounded-0">
                                <li class="list-group-item d-flex justify-content-between align-items-center font-weight-bold">
                                    <ul class="items">
                                        <li>Subtotal ({{$quantity}} items)</li>
                                        <li>Taxes</li>
                                        <li>Total</li>
                                        <li>Shipping Fee</li>
                                        <li>Order Total</li>
                                    </ul>
                                    <ul class="amount">
                                        <li>৳{{ $subtotal }}</li>
                                        <li>৳{{ $tax }}</li>
                                        <li>৳{{ $subtotal + $tax }}</li>
                                        <li>৳{{ $shippingFee }}</li>
                                        <li>৳{{ $subtotal + $tax + $shippingFee }}</li>
                                    </ul>
                                </li>
                                <li class="list-group-item text-center">  <a class="btn btn-primary col-md-12 text-uppercase" href="{{ url('/checkout') }}">Go to checkout</a></li>
                            </ul>
                        </div>
                    @else
                        <div class="col-md-12">
                            <h2 class="text-danger font-weight-bold mt-5" align="center">Your cart is empty!</h2>
                        </div>
                    @endif
                </div>
                <div class="col-md-12 mt-5 mb-5">
                    <a class="btn btn-primary col-md-3 text-uppercase" href="{{ url('/') }}">Continue Shopping</a>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('footer-script')
    <script type="text/javascript">
        /*************************************
         UPDATE CART SCRIPTING START HERE
         ***********************************/
        $(document.body).ready(function () {
            $('.update-item').click(function (e) {
                let parentHtml = $(this).parent().parent();
                let productId = parentHtml.find('.product-item').val();
                let quantity = parentHtml.find('.qty-input').val();

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    url: "/update-cart-items",
                    method: "POST",
                    data: {
                        'quantity': quantity,
                        'product_id': productId,
                    },
                    success: function (data) {
                        if(data){
                            alertify.set('notifier','position', 'top-left');
                            alertify.success(`<strong class="text-white"><span class="fa fa-check-circle"></span> Success!<br/> Product updated successfully.</strong> `);
                            $(".updateCart").html(data.cart_html);
                            $(".small-cart, .cartProduct").html(data.html);
                            $(".totalItem").html(data.totalItem);
                            $(".total-item").html(`(${data.totalItem})`);
                            $(".totalAmount").html(`৳${data.totalAmount}`);
                        }
                    },
                    error: function (data) {
                        alert(data);
                    }
                });
            });
        });
    </script>
@endsection
