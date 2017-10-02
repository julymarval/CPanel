
<!DOCTYPE html>
<html lang="en">
<head>
  <title>RADIO SABOR LATINO 93.5 FM</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}">

</head>
<body id="home" data-spy="scroll" data-target=".navbar" data-offset="60">

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="{{ url('/') }}">
        <img src="{{asset('/images/logo3.png')}}"  >
      </a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="{{ url('/') }}">HOME</a></li>
        <li><a href="{{ route('about') }}">ABOUT</a></li>
        <li><a href="{{route('events.index')}}">EVENTS</a></li>
        <li><a href="{{route('sales.index')}}">TIANGUIS</a></li>
        <li class="active"><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RGJJ7RX543MDQ"target="_blank"><span class="glyphicon glyphicon-heart-empty red"></span> DONATE </a></li>
      </ul>
    </div>
  </div>
</nav>
    @yield('content')