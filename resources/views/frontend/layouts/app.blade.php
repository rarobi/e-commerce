<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('meta')
    <title>{{ env('APP_NAME','Application') }} | @yield('title','Home')</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="/assets/frontend/img/favicon.ico" />

    <!--***********************************************
       vendor min css,plugins min css,style min css
     ***********************************************-->
    {!! Html::style('assets/frontend/css/vendor/vendor.min.css') !!}
    {!! Html::style('assets/frontend/css/plugins.min.css') !!}
    {!! Html::style('assets/frontend/css/style.min.css') !!}
    {!! Html::style('assets/backend/dist/css/custom.css') !!}
    {!! Html::style('assets/frontend/css/bootstrap-side-modals.css') !!}
    {!! Html::style('/assets/frontend/css/alertify.min.css') !!}

    @yield('header-css')

</head>

<body>
<!-- offcanvas-overlay start -->
<div class="offcanvas-overlay"></div>
<!-- offcanvas-overlay end -->

<!-- offcanvas-mobile-menu start -->
@include('frontend.includes.partials.mobile-menu')
<!-- offcanvas-mobile-menu end -->

<!-- header start -->
<header>
    @include('frontend.includes.header')
</header>
<!-- header end -->

<div class="row">
    <div class="col-md-12">
        @yield('content')
    </div>
</div>

<!-- news letter section start -->
@include('frontend.includes.partials.newsletter')
<!-- news letter section end -->

<!-- footer strat -->
<footer>
    @include('frontend.includes.footer')
</footer>
<!-- footer end -->

<!-- modals start -->

<!-- Modal -->

<!-- quick view modal -->
@include('frontend.includes.partials.modal.quick-view')
<!-- second modal -->

<div class="modal fade style2" id="compare" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h5 class="title"><i class="fa fa-check"></i> Product added to compare.</h5>
            </div>
        </div>
    </div>
</div>

@include('frontend.includes.partials.modal.add-cart')

<!--***********************************
        vendor,plugins and main js
     ***********************************-->
{!! Html::script('assets/frontend/js/vendor/vendor.min.js') !!}
{!! Html::script('assets/frontend/js/plugins.min.js') !!}
{!! Html::script('assets/frontend/js/main.js') !!}
{!! Html::script('/assets/frontend/js/alertify.min.js') !!}
@yield('footer-script')
<script type="text/javascript">

    /*************************************
     SCROLL LOAD MORE SCRIPT START HERE
     ***********************************/
    let page = 1;

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height()) {
            page++;
            loadMoreData(page);
        }
    });

    function loadMoreData(page) {
        $.ajax({
            url: '?page=' + page,
            type: "GET",
            beforeSend: function () {
                $('.ajax-load').show();
            }
        })
            .done(function (data) {
                if (data.html.length == 0) {
                    $('.ajax-load').html(`<p class="p-2 text-danger" style="background: #e1e1e1;">No product found!</p>`);
                    return;
                }
                $('.ajax-load').hide();
                $("#post-data").append(data.html);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('server not responding...');
            });
    }

    /*************************************
     ADD TO CART SCRIPTING START HERE
     ***********************************/
    $(document.body).ready(function () {
        $('.add-to-cart').click(function (e) {
            let productId = $(this).attr('data-url');
            let quantity = $(this).closest('.single-product').find('.qty-input').val();

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

    /**************************************
     DELETE CART ITEM SCRIPTING START HERE
     **************************************/

    $(document.body).on('click','.remove-item',function () {
        let productId = $(this).attr("data-item");

        $.ajax({
            type: 'GET',
            url: "/remove-items/"+ productId,
            data: {'_token': $('input[name=_token]').val()
            },
            success: function (data) {
               if(data){
                   $(".small-cart, .cartProduct").html(data.html);
                   $(".totalItem").html(data.totalItem);
                   $(".updateCart").html(data.cart_html);
                   $(".total-item").html(`(${data.totalItem})`);
                   $(".totalAmount").html(`৳${data.totalAmount}`);
               }
            },

            error: function (data) {
                alert(data);
            }
        });
    });

    /******************************
     QUICK VIEW MODAL START HERE
     *****************************/
    $(document.body).on('click','.quick-view',function () {
        let productId = $(this).attr("data-item");

        $.ajax({
            type: 'GET',
            url: "/quick-view/"+ productId,
            data: {'_token': $('input[name=_token]').val()},
            success: function (data) {
                if(data){
                    $(".quick-view-body").html(data.html);
                }
            },
            complete:function(data){
                $(".ajax-load").hide();
            },

            error: function (data) {
                alert(data);
            }
        });
    });

    /******************************
     ADD TO WISHLIST START HERE
     *****************************/
    $(document).on('click','.wishlist',function () {
        let productId = $(this).attr("data-item");

        $.ajax({
            type: 'POST',
            url: "/add-to-wishlist/"+ productId,
            data: {'_token': $('input[name=_token]').val()},
            success: function (response) {
                if (response.success) {
                    $('.total-wishlist-item').html(`${response.totalWishlistItem}`);
                    $('.wishlist-item').html(`(${response.totalWishlistItem})`);
                    alertify.set('notifier','position', 'top-left');
                    alertify.success(`<strong class="text-white"><span class="fa fa-check-circle"></span> Success!<br/> ${response.status}</strong> `);
                }
                if(!response.success){
                    alertify.set('notifier','position', 'top-left');
                    alertify.error(`<strong class="text-white"><span class="fa fa-exclamation-triangle"></span> Opps!<br/> ${response.status}</strong> `);
                }
            },

            error: function (data) {
                alert(data);
            }
        });
    });

    /****************************************************************
     IF NOT AUTH CUSTOMER REDIRECT LOGIN PAGE DURING ADD TO WISHLIST
     ****************************************************************/
    $(document).on('click','.not-auth-user',function () {
        let redirectUrl = $(this).attr("data-href");
        location.href = redirectUrl;
    });

</script>

</body>
</html>
