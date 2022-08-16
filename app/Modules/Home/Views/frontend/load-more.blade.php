@if(count($products) > 0)
    @foreach($products as $product)
        <div class="col-md-3">
            <div class="product-list mb-4rem">
                <div class="single-product">
                    @if($product->discount)<span class="badge badge-danger cb3">-{{ $product->discount }}%</span>@endif
                    {!! Form::hidden('quantity',1,['class'=>'qty-input']) !!}
                    <div class="product-thumbnail position-relative">
                        <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($product->id)) }}">
                            <img src="{{ (!$product->photo_path)?url('/assets/frontend/img/not-found.png') : url($product->photo_path) }}" alt="{{ $product->name }}" height="255" width="100%">
                        </a>
                        <!-- product links -->
                        <ul class="product-links style1 flex-column d-flex justify-content-center">
                            <li>
                                @if(auth()->user())
                                    <a class="wishlist pointer" data-item="{{ $product->id }}">
                                        <span> <i class="ion-ios-heart-outline"></i> </span>
                                    </a>
                                @else
                                    <a class="not-auth-user pointer" data-href="{{ url('/login') }}">
                                        <span> <i class="ion-ios-heart-outline"></i> </span>
                                    </a>
                                @endif
                            </li>

                            <li>
                                <a class="quick-view" style="cursor: pointer" data-item="{{ $product->id }}" data-toggle="modal" data-target="#quick-view">
                                    <span data-toggle="tooltip" data-placement="bottom" title="Quick view" data-original-title="Quick view">
                                        <i class="ion-ios-search-strong"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a style="cursor: pointer" class="add-to-cart" data-url="{{ $product->id }}" data-toggle="modal" data-target="#right_modal_sm">
                                    <span data-toggle="tooltip" data-placement="bottom" title="Add To Cart">
                                        <i class="ion-bag"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="product-desc pt-2rem position-relative text-center">
                        <h3 class="title">
                            <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($product->id)) }}">{{ $product->name }}</a>
                        </h3>

                        @if($product->discount) <h6 class="product-price"><del>৳{{ $product->price }}</del> <span class="text-danger ml-1">৳{{ $product->price - ($product->discount / 100)*$product->price }}</span></h6> @endif
                        @if(!$product->discount) <h6 class="product-price">৳{{ $product->price }}</h6> @endif
                        <button class="pro-btn text-justify add-to-cart" data-url="{{ $product->id }}" data-toggle="modal" data-target="#right_modal_sm">add to cart <i class="ion-bag"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif


