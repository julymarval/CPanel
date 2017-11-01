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
        <a class="col-md-12 col-sm-12 col-xs-12 gutter">
            <a class="shows">
                <div class="pull-right">
                    <a class="btn btn-default btn-success btn-md" href="{{route('events.create')}}">
                    NEW <i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                </div>
                <h2>Events</h2>
                <table class="table table-bordered table-striped">
                    <thead >
                        <tr class="bg-info ">
                            <th></th>
                            <th style="text-align: center">Name</th>
                        </tr>
                    </thead>        
                    <tbody id="list-items"> 
                        @foreach ($events as $event)
                            <tr>
                                <td style="width:140px; text-align: center">
                                    <a class="btn btn-sm btn-default" href="{{route('events.show', $event -> id)}}"><i class="icon-trash glyphicon glyphicon-eye-open text-primary"></i></a>
                                    <a class="btn btn-sm btn-default" href="{{route('events.edit', $event -> id)}}"><i class="icon-trash glyphicon glyphicon-edit text-primary"></i></a>
                                    <a class="btn btn-sm btn-default" href="{{route('events.destroy', $event -> id)}}" onclick="return confirm('Are you sure you want to delete the event?')">
                                    <i class="icon-trash glyphicon glyphicon-trash text-danger"></i></a>
                                </td>
                                <td> {{$event["name"] }} </td>
                            </tr>
                        @endforeach          
                    </tbody>     
                </table> 
            <div class="text-center">
                {{$events}} 
            </div>
        </div>
    </div>             
    
    <script type="text/javascript" src="{{asset('js/admin.js')}}"></script>

    </body>
@endsection