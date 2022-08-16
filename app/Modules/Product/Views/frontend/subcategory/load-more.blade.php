@if(count($subcategoryProducts) > 0)
    @foreach($subcategoryProducts as $subcategoryProduct)
        <div class="col-md-3">
            <div class="product-list mb-4rem">
                <div class="single-product">
                    {!! Form::hidden('quantity',1,['class'=>'qty-input']) !!}
                    @if($subcategoryProduct->discount)<span class="badge badge-danger cb3">-{{ $subcategoryProduct->discount }}%</span>@endif
                    <div class="product-thumbnail position-relative">
                        <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($subcategoryProduct->id)) }}">
                            <img src="{{ !$subcategoryProduct->photo_path ? url('/assets/frontend/img/not-found.png') : url($subcategoryProduct->photo_path) }}" alt="{{ $subcategoryProduct->name }}" height="255" width="100%">
                        </a>
                        <!-- product links -->
                        <ul class="product-links style1 flex-column d-flex justify-content-center">
                            <li>
                                @if(auth()->user())
                                    <a class="wishlist pointer" data-item="{{ $subcategoryProduct->id }}">
                                        <span> <i class="ion-ios-heart-outline"></i> </span>
                                    </a>
                                @else
                                    <a class="not-auth-user pointer" data-href="{{ url('/login') }}">
                                        <span> <i class="ion-ios-heart-outline"></i> </span>
                                    </a>
                                @endif
                            </li>

                            <li>
                                <a href="{{ url('/quick-view/'.\App\Libraries\Encryption::encodeId($subcategoryProduct->id)) }}" data-toggle="modal" data-target="#quick-view">
                                    <span data-toggle="tooltip" data-placement="bottom" title="Quick view" data-original-title="Quick view">
                                        <i class="ion-ios-search-strong"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a style="cursor: pointer" class="add-to-cart" data-url="{{ $subcategoryProduct->id }}" data-toggle="modal" data-target="#right_modal_sm">
                                    <span data-toggle="tooltip" data-placement="bottom" title="Add To Cart">
                                        <i class="ion-bag"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="product-desc pt-2rem position-relative text-center">
                        <h3 class="title">
                            <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($subcategoryProduct->id)) }}">{{ $subcategoryProduct->name }}</a>
                        </h3>

                        @if($subcategoryProduct->discount) <h6 class="product-price"><del>৳{{ $subcategoryProduct->price }}</del> <span class="text-danger ml-1">৳{{ $subcategoryProduct->price - ($subcategoryProduct->discount / 100)*$subcategoryProduct->price }}</span></h6> @endif
                        @if(!$subcategoryProduct->discount) <h6 class="product-price">৳{{ $subcategoryProduct->price }}</h6> @endif
                        <button class="pro-btn add-to-cart" data-url="{{ $subcategoryProduct->id }}" data-target="#right_modal_sm" data-toggle="modal">add to cart <i class="ion-bag"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
