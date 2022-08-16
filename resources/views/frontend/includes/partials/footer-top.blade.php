<!-- footer top start -->
<div class="address py-6rem bg-white">
    <div class="container">
        <div class="row">
            <div class="col-12 col-sm-7 col-md-4 my-3">
                <div class="address-widget">
                    <div class="media">
                        <span class="address-icon">
                            <i class="ion-ios-location-outline"></i>
                        </span>
                        <div class="media-body">
                            <h4 class="title">373/274, Tejgoan Industrial Area, Dhaka-1208</h4>
                            {{-- <p class="text">Contact Info!</p> --}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-5 col-md-4 my-3">
                <div class="address-widget">
                    <div class="media">
                        <span class="address-icon">
                            <i class="ion-ios-email-outline"></i>
                        </span>
                        <div class="media-body">
                            <h4 class="title"><a href="mailto:{{ session()->get('company.company_email') }}">{{ session()->get('company.company_email') }}</a></h4>
                            <p class="text">info@ieszone.com</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-md-4 my-3">
                <div class="address-widget">
                    <div class="media">
                        <span class="address-icon">
                            <i class="ion-ios-telephone-outline"></i>
                        </span>
                        <div class="media-body">
                            <h4 class="title"><a href="tel:{{ session()->get('company.company_phone') }}">{{ session()->get('company.company_phone') }}</a></h4>
                            <p class="text">01XXXXXXXXX</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- footer top end -->
