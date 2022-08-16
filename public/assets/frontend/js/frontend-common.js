$(document).ready(function () {

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
                    $('.ajax-load').html(`<p class="p-2" style="background: #e1e1e1;">No product found!</p>`);
                    return;
                }
                $('.ajax-load').hide();
                $("#post-data").append(data.html);
            })
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                alert('server not responding...');
            });
    }
});

/*************************************
 ADD TO CART SCRIPTING START HERE
 ***********************************/
$(document.body).ready(function () {
    $('.add-to-cart').click(function (e) {
        e.preventDefault();
        $(".small-cart, .totalAmount, .cartProduct").empty();

        let productId = $(this).attr('data-url');
        let quantity = $(this).closest('.single-product').find('.qty-input').val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: "/add-cart",
            method: "POST",
            data: {
                'quantity': quantity,
                'product_id': productId,
            },
            success: function (data) {
                alertify.set('notifier','position', 'top-left');
                alertify.success(`<strong class="text-white"><span class="fa fa-check-circle"></span> Success!<br/> Product added successfully.</strong> `);
                $(".small-cart, .cartProduct").html(data.html);
                $(".totalItem").html(data.totalItem);
                $(".totalAmount").html(`৳${data.totalAmount}`);
            },
        });
    });
});
