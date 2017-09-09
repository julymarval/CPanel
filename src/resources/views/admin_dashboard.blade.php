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
                            <li class="active"><a href="#"><i class="fa fa-home" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Home</span></a></li>
                            <li><a href="#"><i class="fa fa-tasks" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Shows</span></a></li>
                            <li><a href="#"><i class="fa fa-bar-chart" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Tianguis</span></a></li>
                            <li><a href="#"><i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Events</span></a></li>
                            <li><a href="#"><i class="fa fa-calendar" aria-hidden="true"></i><span class="hidden-xs hidden-sm">Users</span></a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-10 col-sm-11 display-table-cell v-align">
                    <!--<button type="button" class="slide-toggle">Slide Toggle</button> -->
                    <div class="row">
                        <header>
                            <div class="col-md-7">
                                <nav class="navbar-default pull-left">
                                    <div class="navbar-header">
                                        <button type="button" class="navbar-toggle collapsed" data-toggle="offcanvas" data-target="#side-menu" aria-expanded="false">
                                            <span class="sr-only">Toggle navigation</span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></span>
                                            <span class="icon-bar"></spadmin/authenticate#an>
                                        </button>
                                    </div>
                                </nav>
            
                            </div>
                            <div class="col-md-5">
                                <div class="header-rightside">
                                    <ul class="list-inline header-top pull-right">
                                        
                                        <li class="dropdown">
                                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                               <i class="fa fa-user" aria-hidden="true"></i><span class="hidden-xs hidden-sm">User</span></a>
                                                <b class="caret"></b></a>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <div class="navbar-content">
                                                        <span>JS Krishna</span>
                                                        <p class="text-muted small">
                                                            me@jskrishna.com
                                                        </p>
                                                        <div class="divider">
                                                        </div>
                                                        <a href="#" class="view btn-sm active">View Profile</a>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </header>
                    </div>
                    <div class="user-dashboard">
                        <h1>Hello, {{$user}}</h1>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-xs-12 gutter">
    
                                <div class="sales">
                                    <h2>Lastest Events</h2>
    
                                    <div class="row">
        
        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th>Name</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            
            <tbody id="list-itens">
                
                @foreach ($events as $event)
                    <tr>
                        <td> {{ $event["name"] }} </td>
                        <td> {{ $event["date"] }} </td>
                        <td> {{$event["description"]}} </td>
                    </tr>
                @endforeach      
                
            </tbody>    
            
        </table>
        
        
        
    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-xs-12 gutter">
    
                                <div class="sales report">
                                    <h2>Lastest Sales</h2>
                                     <div class="row">
        
        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th>Name</th>
                    <th>Price</th>
                    <th>Description</th>
                </tr>
            </thead>
            
            <tbody id="list-itens">
                
                @foreach ($sales as $sale)
                    <tr>
                        <td> {{ $sale["name"] }} </td>
                        <td> {{ $sale["price"] }} </td>
                        <td> {{$sale["description"]}} </td>
                    </tr>
                @endforeach               
                
            </tbody>    
            
        </table>
        
        
        
    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
        </div>
    
    
    
        
        <script type="text/javascript" src="{{asset('js/admin.js')}}"></script>

    </body>