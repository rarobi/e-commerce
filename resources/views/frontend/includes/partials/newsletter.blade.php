<!-- news letter section start -->
<section class="news-letter-section bg-primary pt-3rem pb-2rem">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-12 col-md-6 col-lg-auto mb-3">
                <div class="nletter-title">
                    <h2 class="title">Subscribe For Newsletters</h2>
                    <p class="text">Be the First to Know. Subscribe for newsletter today !</p>
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-auto mb-3">
                <div class="nletter-form">
                    {!! Form::open(['url'=>'/subscribers','method'=>'POST','class'=>'form-inline position-relative','id'=>'subscribeForm']) !!}
                    {!! Form::email('email','',['class'=>'form-control email','placeholder'=>'Enter your E-mail to subscribe...','required'=>true]) !!}
                    <button class="btn btn-dark nletter-btn actionButton" type="submit">Subscribe</button>
                    {!! Form::close() !!}
                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-auto mb-3">
                <div class="social-network">
                    <ul class="d-flex">
                        <li><a href="//{{ session()->get('company.facebook_link') }}" target="_blank"><span class="ion-social-facebook"></span></a></li>
                        <li><a href="//{{ session()->get('company.twitter_link') }}" target="_blank"><span class="ion-social-twitter"></span></a></li>
                        <li><a href="//{{ session()->get('company.youtube_link') }}" target="_blank"><span class="ion-social-youtube"></span></a></li>
                        <li><a href="//{{ session()->get('company.google_plus_link') }}" target="_blank"><span class="ion-social-google"></span></a></li>
                        <li class="mr-0"><a href="//{{ session()->get('company.instagram_link') }}" target="_blank"><span class="ion-social-instagram"></span></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- news letter section end -->
