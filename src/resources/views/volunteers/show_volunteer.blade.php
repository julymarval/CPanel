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
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12 gutter">
    
                                <div class="sales">
                                    <h2>{{$volunteer -> name}}</h2>
    
                                    <div class="row">
        
        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th style="text-align: center">Image</th>
                    <th style="text-align: center">Description</th>
                    <th style="text-align: center">Status</th>
                </tr>
            </thead>
            
            <tbody id="list-itens">
                
                <td align="center"><img src="/images/volunteers/{{$volunteer["image"]}}"></td>
                <td> {{$volunteer["description"] }} </td>
                <td> {{$volunteer["status"] }} </td>
              
            </tbody>    
            
        </table>

        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th style="text-align: center">Events</th>
                </tr>
            </thead>
            
            <tbody id="list-itens">
                
                @foreach ($my_events as $event)
                    <tr>
                        <td> {{$event}} </td>
                    </tr>
                @endforeach      
                
            </tbody>    
            
        </table>

        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th style="text-align: center">Shows</th>
                </tr>
            </thead>
            
            <tbody id="list-itens">
                
                @foreach ($my_shows as $show)
                    <tr>
                        <td> {{$show}} </td>
                    </tr>
                @endforeach      
                
            </tbody>    
            
        </table>

         <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th style="text-align: center">Sponsors</th>
                </tr>
            </thead>
            
            <tbody id="list-itens">
                
                @foreach ($volunteer["sponsor"] as $sponsor)
                    <tr>
                        <td>{{$sponsor["name"]}} </td>
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