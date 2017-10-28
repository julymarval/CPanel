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
            <br>
            <a href={{route('users.index')}}>
                <span class="glyphicon glyphicon-triangle-left">Back</span>
            </a>
            <br>
            <div class="col-md-12 col-sm-12 col-xs-12 gutter">
                <br><br>
                <div class="row vertical-offset-100">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title" style="text-align: center">{{$user -> name}}</h3>
                </div>
                <div class="panel-body">
                    <form id="check" method="POST" enctype="multipart/form-data" action="{{route('users.update', $user -> id)}}">
                        <fieldset>
                            <div class="form-group"> Name
                                <input class="form-control" placeholder={{$user['name']}} name="name" type="text">
                            </div>
                            <div class="form-group">Email
                                <input class="form-control" placeholder={{$user['email']}} name="email" type="email">
                            </div>
                            <div class="form-group">Password
                                <input class="form-control" name="password" type="password">
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
 
    <script type="text/javascript" src="{{asset('js/admin.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js">

    </script>


</body>
@endsection