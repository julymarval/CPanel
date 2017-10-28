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
                                            <span class="icon-bar"></spadmin/authenticate#an>
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
                            <br>
                            <a href={{route('admin.sponsors')}}>
                                <span class="glyphicon glyphicon-triangle-left">Back</span>
                            </a>
                            <br>
                            <div class="col-md-12 col-sm-12 col-xs-12 gutter">
                                <div class="sales">
                                    <h2>Sponsors</h2>
                                    <div class="row">
        
        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th style="text-align: center">Image</th>
                    <th style="text-align: center">Name</th>
                    <th style="text-align: center">Address</th>
                    <th style="text-align: center">Description</th>
                    <th style="text-align: center">Level</th>
                    <th style="text-align: center">Status</th>
                    <th style="text-align: center">Volunteer</th>
                </tr>
            </thead>
            
            <tbody id="list-items">
                
                <td align="center"><img src="/images/sponsors/{{$sponsor["image"]}}"></td>
                <td align="center"> {{$sponsor["name"] }} </td>
                <td align="text-align: center">{{$sponsor["address"]}}</th>
                <td align="center"> {{$sponsor["description"] }} </td>
                <td align="center"> {{$sponsor["level"] }} </td>
                <td align="center"> {{$sponsor["status"] }} </td>
                <td align="center"> {{$volunteer["name"]}} </td>
              
            </tbody>    
            
        </table>

        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th style="text-align: center">Events</th>
                </tr>
            </thead>
            
            <tbody id="list-items">
                
                @foreach ($my_events as $event)
                    <tr>
                        <td> {{$event}} </td>
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