<!DOCTYPE html>
<html>
    <head>
        <title>Dashboard - @yield('title')</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">    
        <link rel="stylesheet" href="{{URL::asset('css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/bootflat.css')}}">
        <link rel="stylesheet" href="{{URL::asset('css/styles.css')}}">
    </head>
    <body>
        <div class="navbar main-nav">
           <div class="container-fluid">
                <div class="navbar-header">
                    <a class="navbar-brand" href="{{ route('home') }}">Dashboard</a>
                </div>
           </div>
        </div>
        <div class="container">
            @yield('content')
        </div>
    </body>
</html>
