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
                <div class="col-md-12 col-sm-12 col-xs-12 gutter">
                    <div class="images">
                        <h2>Gallery: {{$event["name"]}}</h2>
                        <div class="row">
                            @foreach ($images as $image)
                                <div class = "col-md-4">
                                    <div class = "panel panel-default">
                                        <div class = "panel-body">
                                            <img src = "/images/events/{{$event["name"]}}/{{$image["name"]}}" class = "img-responsive">
                                            <br><br>
                                            <div class="btn btn-sm btn-default"><a href="{{route('images.destroy', $image["id"])}}">
                                                <i class="icon-trash glyphicon glyphicon-eye-open text-primary"> <br> Delete </i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
            

        <script type="text/javascript" src="{{asset('js/admin.js')}}"></script>

    </body>

@endsection