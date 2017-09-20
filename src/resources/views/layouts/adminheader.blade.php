<head>
<title>RADIO SABOR LATINO 93.5 FM</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
<link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<link rel="stylesheet" type="text/css" href="{{asset('/css/admin.css')}}">
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-T8Gy5hrqNKT+hzMclPo118YTQO6cYprQmhrYwIiQ/3axmI1hQomh7Ud2hPOy8SP1" crossorigin="anonymous">

</head>
    <body class="home">
        <div class="container-fluid display-table">
            <div class="row display-table-row">
                <div class="col-md-2 col-sm-1 hidden-xs display-table-cell v-align box" id="navigation">
                    <div class="logo">
                        <a hef="home.html">     
                            <img src="{{asset('/images/logo3.png')}}"  >
                        </a>
                    </div>
                    <div class="navi">
                        <ul>
                            <li><a href="{{route('dashboard')}}"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Home</span></a></li>
                            <li><a href="{{route('admin.shows')}}"><i class="fa fa-music" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Shows</span></a></li>
                            <li><a href="{{route('admin.sales')}}"><i class="fa fa-usd" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Tianguis</span></a></li>
                            <li><a href="{{route('admin.events')}}"><i class="fa fa-calendar" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Events</span></a></li>
                            <li><a href="{{route('admin.sponsors')}}"><i class="fa fa-suitcase" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Sponsors</span></a></li>
                            <li><a href="{{route('admin.volunteers')}}"><i class="fa fa-users" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Volunteers</span></a></li>
                        </ul>
                    </div>
                </div>
                @yield('content')