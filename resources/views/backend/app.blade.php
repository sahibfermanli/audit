<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="parcel/css/bootstrap.css">
    <link rel="stylesheet" href="parcel/css/main.css">
    @yield('css')
</head>
<body>
<nav class="navbar navbar-fixed-top navbar-color">
    <div class="container-fluid mycontainer">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
        </div>
        <div class="collapse navbar-collapse" id="myNavbar">
            <ul class="nav navbar-nav" id="menu-links">
                @switch(Auth::user()->authority())
                    @case(1)
                    <li><a href="/users">Users</a></li>
                    @break
                    @case(2)
                    <li><a href="/receive">Receive</a></li>
                    <li><a href="/delivery">Delivery</a></li>
                    @break
                    @case(3)
                    <li><a href="/checkout">Checkout</a></li>
                    @break
                    @case(4)
                    <li><a href="/arrive">Arrive</a></li>
                    <li><a href="/withheld">Withheld status</a></li>
                    @break
                @endswitch
                <li><a href="/packages">Packages</a></li>
                <li><a href="/archive">Archive</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a onclick="location.reload();" style="cursor: pointer;"><span class="glyphicon glyphicon-refresh"></span> Refresh</a></li>
                <li><a href="/logout"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="main">
    @yield('content')
</div>


{{--<div class="col-md-12 footer">--}}
    {{--<span>Created by Sahib and Design by Narmin</span>--}}
{{--</div>--}}

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="parcel/js/bootstrap.js"></script>
{{--<script src="parcel/js/jquery-3.3.1.js"></script>--}}

<script>
    var pathname = window.location.pathname;

    $('#menu-links').each(function(){
        $(this).find('li').each(function(){
            var current = $(this);
            var menu_link = current.find('a').attr('href');
            if (menu_link === pathname) {
                current.addClass('active');
            }
        });
    });
</script>

@yield('js')
</body>
</html>