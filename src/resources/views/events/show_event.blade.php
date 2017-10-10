@extends('layouts.adminheader')
@section('content')
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
                                            <span class="icon-bar"></span>
                                        </button>
                                    </div>
                                </nav>
            
                            </div>
  
                        </header>
                    </div>
                    <div class="user-dashboard">
                    <!-- Right Side Of Navbar -->
                        <ul class="nav navbar-nav navbar-right">
                            <!-- Authentication Links -->
                            @if (Auth::guest())
                                
                            @else
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                        {{ Auth::user()->name }} <span class="caret"></span>
                                    </a>

                                    <ul class="dropdown-menu" role="menu">
                                        <li>
                                            <a href="{{ route('logout') }}"
                                                onclick="event.preventDefault();
                                                            document.getElementById('logout-form').submit();">
                                                Logout
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                                {{ csrf_field() }}
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        </ul>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 gutter">
    
                                <div class="sales">
                                    <h2>{{$event["name"]}}</h2>
    
                                    <div class="row">
        
        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th style="text-align: center">Image</th>
                    <th style="text-align: center">Description</th>
                    <th style="text-align: center">date</th>
                </tr>
            </thead>
            
            <tbody id="list-items">  
                @if(count($images) > 0)
                    <td align="center"><img src="/images/events/{{$images[0] -> name}}">
                        <br><br>
                        <div class="btn btn-sm btn-default"><a href="{{route('images.show', $event["id"])}}">
                                <i class="icon-trash glyphicon glyphicon-eye-open text-primary"> <br> View All </i></a>
                        </div>
                    </td>
                @else
                    <td>
                @endif
                <td> {{$event["description"] }} </td>
                <td> {{$event["date"] }} </td>
              
            </tbody>    
            
        </table>

        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th style="text-align: center">Sponsors</th>
                </tr>
            </thead>
            
            <tbody id="list-items">
                
                @foreach ($my_sponsors as $sponsor)
                    <tr>
                        <td> {{$sponsor}} </td>
                    </tr>
                @endforeach      
                
            </tbody>    
            
        </table>

         <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th style="text-align: center">Volunteers</th>
                </tr>
            </thead>
            
            <tbody id="list-items">
                
                @foreach ($my_volunteers as $volunteer)
                    <tr>
                        <td> {{$volunteer}} </td>
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

@endsection