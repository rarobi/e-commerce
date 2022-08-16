<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('title','Admin Login')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Font Awesome -->
    {!! Html::style('assets/backend/plugins/fontawesome-free/css/all.min.css') !!}
    {!! Html::style('assets/backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css') !!}
    {!! Html::style('assets/backend/dist/css/adminlte.min.css') !!}
    {!! Html::style('assets/frontend/css/theme.css') !!}
</head>
<body class="hold-transition login-page">
<div class="login-box">
@yield('header')
    @yield('content')
</div>
<!-- /.login-box -->

<!-- jQuery -->
{!! Html::script('assets/backend/plugins/jquery/jquery.min.js') !!}
{!! Html::script('assets/backend/plugins/bootstrap/js/bootstrap.bundle.min.js') !!}
{!! Html::script('assets/backend/dist/js/adminlte.min.js') !!}
</body>
</html>
