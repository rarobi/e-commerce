@if(count($categoryProducts) > 0)
    @foreach($categoryProducts as $categoryProduct)
        <div class="col-md-3">
            <div class="product-list mb-4rem">
                <div class="single-product">
                    {!! Form::hidden('quantity',1,['class'=>'qty-input']) !!}
                    @if($categoryProduct->discount)<span class="badge badge-danger cb3">-{{ $categoryProduct->discount }}%</span>@endif
                    <div class="product-thumbnail position-relative">
                        <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($categoryProduct->id)) }}">
                            <img src="{{ !$categoryProduct->photo_path ? url('/assets/frontend/img/not-found.png') : url($categoryProduct->photo_path) }}" alt="{{ $categoryProduct->name }}" height="255" width="100%">
                        </a>
                        <!-- product links -->
                        <ul class="product-links style1 flex-column d-flex justify-content-center">
                            <li>
                                @if(auth()->user())
                                    <a class="wishlist pointer" data-item="{{ $categoryProduct->id }}">
                                        <span> <i class="ion-ios-heart-outline"></i> </span>
                                    </a>
                                @else
                                    <a class="not-auth-user pointer" data-href="{{ url('/login') }}">
                                        <span> <i class="ion-ios-heart-outline"></i> </span>
                                    </a>
                                @endif
                            </li>

                            <li>
                                <a class="quick-view" style="cursor: pointer" data-item="{{ $categoryProduct->id }}" data-toggle="modal" data-target="#quick-view">
                                    <span data-toggle="tooltip" data-placement="bottom" title="Quick view" data-original-title="Quick view">
                                        <i class="ion-ios-search-strong"></i>
                                    </span>
                                </a>
                            </li>
                            <li>
                                <a style="cursor: pointer" class="add-to-cart" data-url="{{ $categoryProduct->id }}" data-toggle="modal" data-target="#right_modal_sm">
                                    <span data-toggle="tooltip" data-placement="bottom" title="Add To Cart">
                                        <i class="ion-bag"></i>
                                    </span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="product-desc pt-2rem position-relative text-center">
                        <h3 class="title">
                            <a href="{{ url('/product-details/'.\App\Libraries\Encryption::encodeId($categoryProduct->id)) }}">{{ $categoryProduct->name }}</a>
                        </h3>

                        @if($categoryProduct->discount) <h6 class="product-price"><del>৳{{ $categoryProduct->price }}</del> <span class="text-danger ml-1">৳{{ $categoryProduct->price - ($categoryProduct->discount / 100)*$categoryProduct->price }}</span></h6> @endif
                        @if(!$categoryProduct->discount) <h6 class="product-price">৳{{ $categoryProduct->price }}</h6> @endif
                        <button class="pro-btn add-to-cart" data-url="{{ $categoryProduct->id }}" data-target="#right_modal_sm" data-toggle="modal">add to cart <i class="ion-bag"></i></button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif
