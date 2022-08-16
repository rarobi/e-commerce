<!-- brand slider start -->
@if($brands->count() > 0)
    <div class="brand-slider-section pb-6rem bg-white">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="brand-init style1">
                        @foreach($brands as $brand)
                            <div class="slider-item">
                                <div class="single-brand">
                                    <a href="//{{ $brand->website }}" class="brand-thumb" target="_blank">
                                        <img src="{{ url('/uploads/brand-photos/'.$brand->photo) }}" alt="{{ $brand->name }}" height="60" width="100%" title="{{ $brand->name }}">
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
<!-- brand slider end -->
