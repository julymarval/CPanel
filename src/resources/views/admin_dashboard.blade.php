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
@endsection