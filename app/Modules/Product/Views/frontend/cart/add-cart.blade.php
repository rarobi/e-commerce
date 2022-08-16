@if(isset($cart) && ( count($cart) > 0))
    @php
        $subtotal = 0;
        $tax = 0;
    @endphp
    <div class="small-cart-item">
        @foreach($cart as $cartItemId => $cartItem)
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
            <a class="remove-item remove-icon" style="cursor: pointer" data-item="{{ $cartItemId }}"><i class="ion-close-round"></i></a>
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
@else
    <div class="small-cart-item">
        <h3 class="text-center font-weight-bold text-danger">Your cart is empty!</h3>
    </div>
@endif
