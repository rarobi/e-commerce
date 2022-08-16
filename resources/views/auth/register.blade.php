@extends('frontend.layouts.app')
@section('header-css')
    {!! Html::style('assets/backend/plugins/datetimepicker/css/bootstrap-timepicker.min.css') !!}
    {!! Html::style('assets/backend/plugins/datetimepicker/css/bootstrap-datetimepicker-standalone.css') !!}
    {!! Html::style('assets/backend/plugins/datetimepicker/css/bootstrap-datetimepicker2.min.css') !!}
@endsection
@section('content')
    <!-- bread crumb start -->
    <nav class="breadcrumb-section bg-white pt-5 pb-6rem">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">New Account</li>
                    </ol>
                </div>
            </div>
        </div>
    </nav>
    <!-- bread crumb end -->
    <!-- my-account start -->
    <div class="my-account pb-6rem">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h3 class="title"> Create a new account</h3>
                    @include('backend.includes.messages')
                    {!! Form::open(['url'=>'/signup','method'=>'post','class'=>'log-in-form']) !!}
                    <div class="form-group row">
                        {!! Form::label('name','Full Name',['class'=>'col-md-3 col-form-label required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::text('name','',['class'=>$errors->has('name')?'form-control is-invalid':'form-control','placeholder'=>'Enter your name']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('mobile','Phone',['class'=>'col-md-3 col-form-label required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::text('mobile','',['class'=>$errors->has('mobile')?'form-control is-invalid':'form-control','placeholder'=>'Phone number']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('email','Email',['class'=>'col-md-3 col-form-label required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::email('email','',['class'=>$errors->has('email')?'form-control is-invalid':'form-control','placeholder'=>'Email Address']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('password','Password',['class'=>'col-md-3 col-form-label required-star']) !!}
                        <div class="col-md-6">
                            <div class="input-group mb-2 mr-sm-2">
                                {!! Form::password('password',['class'=>$errors->has('password')?'form-control is-invalid':'form-control','id'=>'password','placeholder'=>'******']) !!}
                                <div class="input-group-prepend">
                                    <sapn toggle="#password" class="input-group-text btn-dark3 fa fa-eye toggle-password pointer"></sapn>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('confirm_password','Confirm Password',['class'=>'col-md-3 col-form-label required-star']) !!}
                        <div class="col-md-6">
                            <div class="input-group mb-2 mr-sm-2">
                                {!! Form::password('confirm_password',['class'=>$errors->has('password')?'form-control is-invalid':'form-control','id'=>'confirm-password','placeholder'=>'******']) !!}
                                <div class="input-group-prepend">
                                    <sapn toggle="#confirm-password" class="input-group-text btn-dark3 fa fa-eye toggle-password pointer"></sapn>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('date_of_birth','Birthday',['class'=>'col-md-3 col-form-label required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::text('date_of_birth','',['class'=>'required form-control date-picker '.($errors->has('date_of_birth')?'is-invalid':''),'placeholder'=>'YYYY-MM-DD']) !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        {!! Form::label('gender','Gender',['class'=>'col-md-3 col-form-label required-star']) !!}
                        <div class="col-md-6">
                            {!! Form::select('gender',['Male'=>'Male','Female'=>'Female'],'',['class'=>$errors->has('gender')?'form-control is-invalid':'form-control','placeholder'=>'Select gender']) !!}
                        </div>
                    </div>
                    <div class="form-group row pb-3 text-center">
                        <div class="col-md-6 offset-md-3">
                            <div class="login-form-links">
                                <a href="{{ url('/forget-password') }}" class="for-get">Forgot your password?</a>
                                <div class="sign-btn">
                                    {!! Form::submit('Sign up',['class'=>'btn btn-dark3']) !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group row text-center">
                        <div class="col-12">
                            <div class="border-top">
                                <a href="{{ url('/login') }}" class="no-account">Already have an account?</a>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- my-account end -->
@endsection
@section('footer-script')
    {!! Html::script('assets/backend/plugins/datetimepicker/js/bootstrap-timepicker.min.js') !!}
    {!! Html::script('assets/backend/plugins/datetimepicker/js/bootstrap.min.js') !!}
    {!! Html::script('assets/backend/plugins/datetimepicker/js/moment.min.js') !!}
    {!! Html::script('assets/backend/plugins/datetimepicker/js/bootstrap-datetimepicker2.min.js') !!}
    <script type="text/javascript">
        $('.date-picker').datetimepicker({
            format: 'YYYY-MM-DD',
            icons: {
                date: "fa fa-calendar",
                previous:"fa fa-angle-left",
                next:"fa fa-angle-right"
            }
        });
    </script>
@endsection
