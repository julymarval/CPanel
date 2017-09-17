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
                                <div class="events">
                                    <div class="pull-right">
                                        <a class="btn btn-default btn-success btn-md" href="{{route('events.create')}}">
                                        NEW <i class="fa fa-plus-circle" aria-hidden="true"></i></a>
                                    </div>
                                    <h2>Events</h2>
                                    <div class="row">
        
        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th></th>
                    <th style="text-align: center">Name</th>
                </tr>
            </thead>
            
            <tbody id="list-itens">
                
                @foreach ($events as $event)
                    <tr>
                        <td style="width:140px; text-align: center">
                            <div class="btn btn-sm btn-default"><a href="{{route('events.show', $event -> id)}}"><i class="icon-trash glyphicon glyphicon-eye-open text-primary"></i></div>
                            <div class="btn btn-sm btn-default"><a href="{{route('events.edit', $event -> id)}}"><i class="icon-trash glyphicon glyphicon-edit text-primary"></i></div>
                            <div class="btn btn-sm btn-default"><a href="{{route('events.destroy', $event -> id)}}"><i class="icon-trash glyphicon glyphicon-trash text-danger"></i></div>
                        </td>
                        <td> {{$event["name"] }} </td>
                    </tr>
                @endforeach      
                
            </tbody>    
            
        </table>
        
        
        {{$events}}
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