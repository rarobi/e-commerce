<!-- add to cart modal -->
<div class="modal modal-right fade" id="right_modal_sm" tabindex="-1" role="dialog" aria-labelledby="right_modal_sm">
    <div class="modal-dialog modal-sm" role="document">
        <div class="modal-content" style="overflow-y: initial !important; border-left: 2px solid #fed700">
            <div class="modal-header bg-primary">
                <h3 class="modal-title text-bold"> <span class="ion-bag"></span> Your Cart</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body cartProduct" style="max-height: calc(100vh - 200px); overflow-y: auto;">
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
            <div class="modal-footer modal-footer-fixed">
                <div class="cart-buttons pt-1 col-md-12">
                    <a href="{{ url('/') }}" class="btn btn-primary btn-block">Continue Shopping</a>
                </div>
                <div class="cart-buttons pt-1 col-md-12">
                    <a href="{{ url('/cart') }}" class="btn btn-primary btn-block">View Cart</a>
                </div>
                <div class="cart-buttons pt-1 col-md-12">
                    <a href="{{ url('/checkout') }}" class="btn btn-primary btn-block">Checkout</a>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- add to cart modal -->
