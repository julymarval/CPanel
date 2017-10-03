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
                                <li><a href="{{ route('login') }}">Login</a></li>
                                <li><a href="{{ route('register') }}">Register</a></li>
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
    
       <br>
                    <br>
                                     <div class="row vertical-offset-100">
        <div class="col-md-8 col-md-offset-2">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title" style="text-align: center">{{$sponsor -> name}}</h3>
           </div>
            <div class="panel-body">
                <form id="check" method="POST" enctype="multipart/form-data" action="{{route('sponsors.update', $sponsor -> id)}}">
                    <fieldset>
                        <div class="form-group">
                            <input class="form-control" placeholder="Name" name="name" type="text">
                        </div>
                        <div class="form-group">
                            <input class="form-control" placeholder="description" name="description" type="text" >
                        </div>
                        <div>
                            <input class="form-control" placeholder="image" name="image" type="file">
                        </div>
                        <div class="form-group">
                            <label for="sel1">Select a Status:</label>

                            <select class="form-control"  name="status" required>
                                <option disabled selected value> -- select an option -- </option>
                                <option value="Activo">Activo</option>
                                <option value="Inactivo">Inactivo</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="sel1">Select a Level:</label>

                            <select class="form-control"  name="level" required>
                                <option disabled selected value> -- select an option -- </option>
                                <option value="Cobre">Cobre</option>
                                <option value="Plata">Plata</option>
                                <option value="Oro">Oro</option>
                                <option value="Platino">Platino</option>
                                <option value="Diamante">Diamante</option>
                            </select>
                        </div>
                        <div class="form-group">
                            {!! Form::label('volunteers', 'Volunteers') !!}
                            {!! Form::select('volunteer_id',[null => ' -- select a volunteer --'] + $volunteers,
                            " ",['volunteers' => 'id', 'class' => 'form-control',
                            'single',null]) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('events', 'Events') !!}
                            {!! Form::select('event_id[]',$events,
                            " ",['events' => 'id', 'class' => 'form-control',
                            'multiple',null]) !!}
                        </div>
                        <br>
                <input class="btn btn-lg btn-success btn-block" type="submit" value="Update">
                    </fieldset>
                </form>
            </div>
        </div>
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