@if($wishlistItems->count() > 0)
    <div class="table-responsive pt-4">
        <table class="table">
            <thead class="thead-light">
            <tr>
                <th class="text-center" scope="col">products</th>
                <th class="text-center" scope="col">name</th>
                <th class="text-center" scope="col">Price</th>
                <th class="text-center" scope="col">action</th>
                <th class="text-center" scope="col">Checkout</th>
            </tr>
            </thead>
            <tbody>
            @foreach($wishlistItems as $wishlistItem)
                <tr>
                    <th class="text-center" scope="row">
                        <img src="{{ !$wishlistItem->photo_path ? url('/assets/frontend/img/not-found.png') : url($wishlistItem->photo_path) }}" alt="{{ $wishlistItem->name }}" height="80" width="90">
                    </th>
                    <td class="text-center">
                        <span class="whish-title">{{ $wishlistItem->name }}</span>
                    </td>
                    <td class="text-center">
                        <span class="product-price">
                            ৳{{ $wishlistItem->price }}
                        </span>
                    </td>

                    <td class="text-center">
                        <a class="remove pointer" data-item="{{ $wishlistItem->wishlist_id }}"> <span class="trash"><i class="fas fa-trash-alt"></i> </span></a>
                    </td>

                    <td class="text-center">
                        {!! Form::hidden('quantity',1,['class'=>'qty-input']) !!}
                        <button class="btn btn-primary rounded mt-5 mt-sm-0 add-cart-wishlist-item" wishlist-id="{{ $wishlistItem->wishlist_id }}" product-id="{{ $wishlistItem->id }}" data-toggle="modal" data-target="#right_modal_sm">
                            <span class="mr-2"><i class="ion-android-add"></i></span>
                            Add to cart
                        </button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@else
    <div class="col-md-12">
        <h2 class="text-danger font-weight-bold mt-5" align="center">Your wishlist is empty!</h2>
    </div>
@endif

<script type="text/javascript">


    /*************************************
     ADD TO CART FROM WISHLIST START HERE
     ***********************************/
    $(document.body).ready(function () {
        $('.add-cart-wishlist-item').click(function (e) {
            let wishlistId = $(this).attr('wishlist-id');
            let productId = $(this).attr('product-id');
            let quantity = $(this).parent().find('.qty-input').val();

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/add-to-cart/from-wishlist",
                method: "POST",
                data: {
                    'wishlist_id': wishlistId,
                    'product_id': productId,
                    'quantity': quantity,
                },
                success: function (data) {
                    if(data.status){
                        //Cart info
                        $(".small-cart, .cartProduct").html(data.cart_html);
                        $(".totalItem").html(data.totalItem);
                        $(".total-item").html(`(${data.totalItem})`);
                        $(".totalAmount").html(`৳${data.totalAmount}`);

                        //Wishlist info
                        $('.wishlist-items').html(`${data.wishlist_html}`);
                        $('.total-wishlist-item').html(`${data.totalWishlistItem}`);
                        $('.wishlist-item').html(`(${data.totalWishlistItem})`);

                        alertify.set('notifier','position', 'top-left');
                        alertify.success(`<strong class="text-white"><span class="fa fa-check-circle"></span> Success!<br/> Product added to your cart successfully.</strong> `);
                    }
                },
                error: function (data) {
                    alert(data);
                }
            });
        });
    });

    /*************************************
     DELETE WISHLIST ITEM  SCRIPTING HERE
     ***********************************/
    $(document.body).on('click','.remove',function () {
        let wishlistId = $(this).attr("data-item");

        $.ajax({
            type: 'POST',
            url: "/remove-wishlist-item/"+ wishlistId,
            data: {'_token': $('input[name=_token]').val()},
            success: function (data) {
                if(data){
                    $('.wishlist-items').html(`${data.html}`);
                    $('.total-wishlist-item').html(`${data.totalWishlistItem}`);
                    $('.wishlist-item').html(`(${data.totalWishlistItem})`);
                }
            },
            error: function (data) {
                alert(data);
            }
        });
    });
</script>
