<div class="row">
    @if($productPhotos->count() > 0)
        <div class="col-md-8 mx-auto col-lg-5 mb-5 mb-lg-0">
            <div class="product-sync-init mb-20">
                @foreach($productPhotos as $productPhoto)
                    <div class="single-product single-data">
                        <div class="product-thumb">
                            <img src="{{ !$productPhoto->path ? url('/assets/frontend/img/not-found.png') : url($productPhoto->path) }}" alt="{{ $product->name }}" height="382" width="100%">
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="product-sync-nav">
                @foreach($productPhotos as $productPhoto)
                    <div class="single-product mt-1">
                        <div class="product-thumb">
                            <a href="javascript:void(0)">
                                <img src="{{ !$productPhoto->path ? url('/assets/frontend/img/not-found.png') : url($productPhoto->path) }}" alt="{{ $product->name }}" height="75" width="75">
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="col-md-8 mx-auto col-lg-5 mb-5 mb-lg-0">
            <div class="product-sync-init mb-20">
                <div class="single-product single-data">
                    <div class="product-thumb">
                        <img src="{{ url('/assets/frontend/img/not-found.png') }}" alt="{{ $product->name }}" height="382" width="100%">
                    </div>
                </div>
            </div>
        </div>
    @endif
        <div class="col-lg-7 mt-5 mt-md-0">
            <div class="modal-product-info">
                <div class="product-head">
                    <h2 class="title">{{ $product->name }}</h2>
                    <h4 class="sub-title">Category : {{ $product->product_category_name }}</h4>
                    Brand : <a href="//{{ $product->website }}" target="_blank"><span class="edite"> {{ $product->product_brand_name }} </span> <img class="ml-2" src="{{ url('/uploads/brand-photos/'.$product->brand_photo) }}" alt="{{ $product->product_brand_name }}" height="60" width="60" title="{{ $product->product_brand_name }}"></a>
                    {{--                   <div class="star-content">--}}
                    {{--                       <span class="star-on"><i class="fas fa-star"></i> </span>--}}
                    {{--                       <span class="star-on"><i class="fas fa-star"></i> </span>--}}
                    {{--                       <span class="star-on"><i class="fas fa-star"></i> </span>--}}
                    {{--                       <span class="star-on"><i class="fas fa-star"></i> </span>--}}
                    {{--                       <span class="star-on"><i class="fas fa-star"></i> </span>--}}
                    {{--                   </div>--}}
                </div>
                <div class="product-body">

                    @if($product->discount)
                        <span class="regular-price"> <del>৳{{ $product->price }}</del> ৳{{ $product->price - ($product->discount / 100)*$product->price }}</span>
                        <span class="badge badge-dark">Save {{ $product->discount }}%</span>
                    @else
                        <span class="regular-price">৳{{ $product->price }}</span>
                    @endif

                    <p>{{ $product->short_description }}</p>
                </div>
                <div class="product-footer single-data">
                    <div class="product-count style d-flex flex-column flex-sm-row mt-5 mb-5 pt-3">
                        <div class="d-flex count-number">
                            <input type="number" min="1" max="10" step="1" value="1" class="qty">
                            <div class="button-group">
                                <button class="count-btn increment"><i class="fas fa-chevron-up"></i></button>
                                <button class="count-btn decrement"><i class="fas fa-chevron-down"></i></button>
                            </div>
                        </div>
                        <div>
                            <button class="btn btn-primary rounded mt-5 mt-sm-0 add-cart" style="cursor: pointer" data-url="{{ $product->id }}" data-toggle="modal" data-target="#right_modal_sm">
                                <span class="mr-2"><i class="ion-android-add"></i></span>
                                Add to cart
                            </button>
                        </div>
                    </div>
{{--                    <div class="addto-whish-list">--}}
{{--                        <a href="#"><i class="far fa-heart"></i> Add to wishlist</a>--}}
{{--                    </div>--}}
                </div>
            </div>
        </div>
</div>

<script type="text/javascript">
    /*************************************
     PRODUCT PHOTO SLIDER SCRIPTING HERE
     ***********************************/
    $(document.body).ready(function () {
        $(".product-sync-init").slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            infinite: true,
            draggable: false,
            arrows: false,
            dots: false,
            fade: true,
            asNavFor: ".product-sync-nav",
        });
        $(".product-sync-nav").slick({
            dots: false,
            arrows: false,
            infinite: true,
            prevArrow: '<button class="slick-prev"><i class="fas fa-arrow-left"></i></button>',
            nextArrow: '<button class="slick-next"><i class="fas fa-arrow-right"></i></button>',
            slidesToShow: 4,
            slidesToScroll: 1,
            asNavFor: ".product-sync-init",
            focusOnSelect: true,
        });
    });

    /*************************************
     INCREMENT DECREMENT SCRIPTING HERE
     ***********************************/
    $(document.body).ready(function () {
        $(".count-number").each(function() {
            var count = $(this),
                input = count.find('input[type="number"]'),
                increament = count.find(".increment"),
                decreament = count.find(".decrement"),
                minValue = input.attr("min"),
                maxValue = input.attr("max");

            increament.on("click", function() {
                var oldValue = parseFloat(input.val());
                if (oldValue >= maxValue) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue + 1;
                }
                count.find("input").val(newVal);
                count.find("input").trigger("change");
            });

            decreament.on("click", function() {
                var oldValue = parseFloat(input.val());
                if (oldValue <= minValue) {
                    var newVal = oldValue;
                } else {
                    var newVal = oldValue - 1;
                }
                count.find("input").val(newVal);
                count.find("input").trigger("change");
            });
        });
    })


    /*************************************
     ADD TO CART SCRIPTING START HERE
     ***********************************/
    $(document.body).ready(function () {
        $('.add-cart').click(function (e) {

            let productId = $(this).attr('data-url');
            let quantity = $('.qty').val();


            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $.ajax({
                url: "/add-to-cart",
                method: "POST",
                data: {
                    'quantity': quantity,
                    'product_id': productId,
                },
                success: function (data) {
                    if(data.status){
                        $(".small-cart, .cartProduct").html(data.html);
                        $(".totalItem").html(data.totalItem);
                        $(".total-item").html(`(${data.totalItem})`);
                        $(".totalAmount").html(`৳${data.totalAmount}`);
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
</script>
