@extends('frontend.layouts.app')
@section('content')
    <!-- bread crumb start -->
    <nav class="breadcrumb-section bg-white pt-5 pb-6rem">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <ol class="breadcrumb bg-transparent m-0 p-0 align-items-center">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Your account</li>
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
                    <h3 class="title"> Log in to your account</h3>
                    @include('backend.includes.messages')
                    {!! Form::open(['url'=>'login','method'=>'post','class'=>'log-in-form']) !!}
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
                                    {!! Form::password('password',['class'=>$errors->has('password')?'form-control is-invalid':'form-control','id'=>'password-field','placeholder'=>'******']) !!}
                                    <div class="input-group-prepend">
                                        <sapn toggle="#password-field" class="input-group-text btn-dark3 fa fa-eye toggle-password pointer"></sapn>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row pb-3 text-center">
                            <div class="col-md-6 offset-md-3">
                                <div class="login-form-links">
                                    <a href="{{ url('/forget-password') }}" class="for-get">Forgot your password?</a>
                                    <div class="sign-btn">
                                        {!! Form::submit('Sign in',['class'=>'btn btn-dark3']) !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group row text-center">
                            <div class="col-12">
                                <div class="border-top">
                                  <p>Don't have an account?  <a href="{{ url('/register') }}" class="no-account">Sign up</a></p>
                                </div>
                            </div>
                        </div>
                   {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
    <!-- my-account end -->
@endsection
