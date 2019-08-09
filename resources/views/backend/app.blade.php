<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{csrf_token()}}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{asset("backend/css/bootstrap.css")}}">
    <link rel="stylesheet" href="{{asset("css/sweetalert2.min.css")}}">
    <link rel="stylesheet" href="{{asset("backend/css/main.css")}}">
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
                <li><a href="{{route("discrepancies")}}">Discrepancies</a></li>
                <li><a href="{{route("archive_discrepancies")}}">Archive</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
                <li><a onclick="location.reload();" style="cursor: pointer;"><span class="glyphicon glyphicon-refresh"></span> Refresh</a></li>
                <li><a href="{{route("logout")}}"><span class="glyphicon glyphicon-log-out"></span> Log out</a></li>
            </ul>
        </div>
    </div>
</nav>


<div class="main">
    @yield('content')
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="{{asset("backend/js/bootstrap.js")}}"></script>
<script src="{{asset("js/jquery.form.min.js")}}"></script>
<script src="{{asset("js/jquery.validate.min.js")}}"></script>
<script src="{{asset("js/sweetalert2.min.js")}}"></script>
<script src="{{asset("backend/js/main.js")}}"></script>
<script src="{{asset("backend/js/ajax.js")}}"></script>

@yield('js')
</body>
</html>