@extends('layouts.adminheader')
@section('content')

    <div class="col-md-10 col-sm-11 display-table-cell v-align">
                    <!--<button type="button" class="slide-toggle">Slide Toggle</button> -->
        <div class="row">
            <header>
                <div class="col-md-7">
                    <nav class="navbar-default pull-left">
                        <div class="navbar-header">

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
                    <div class="events">
                        <div class="pull-right">
                            <a class="btn btn-default btn-success btn-md" href="{{route('sales.create')}}">
                            NEW <i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                        </div>
                        <h2>Tianguis</h2>

                        <a class="row">

                            <table class="table table-bordered table-striped">
                                <thead >
                                    <tr class="bg-info ">
                                        <th></th>
                                        <th style="text-align: center">Name</th>
                                    </tr>
                                </thead>
                                
                                <tbody id="list-itens"> 
                                    @foreach ($sales as $sale)
                                        <tr>
                                            <td style="width:140px; text-align: center">
                                                <a class="btn btn-sm btn-default" href="{{route('sales.show', $sale -> id)}}"><i class="icon-trash glyphicon glyphicon-eye-open text-primary"></i></a>
                        <a class="btn btn-sm btn-default" href="{{route('sales.edit', $sale -> id)}}"><i class="icon-trash glyphicon glyphicon-edit text-primary"></i></a>
                        <a class="btn btn-sm btn-default" href="{{route('sales.destroy', $sale -> id)}}"><i class="icon-trash glyphicon glyphicon-trash text-danger"></i></a>
                                            </td>
                                            <td> {{$sale["name"] }} </td>
                                        </tr>
                                    @endforeach          
                                </tbody>     
                                {{$sales}}
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