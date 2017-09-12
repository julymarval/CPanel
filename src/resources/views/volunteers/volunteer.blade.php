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
                                    <h2>Volunteers</h2>
    
                                    <div class="row">
        
        <table class="table table-bordered table-striped">
            <thead >
                <tr class="bg-info ">
                    <th></th>
                    <th>Name</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            
            <tbody id="list-itens">
                
                @foreach ($volunteers as $volunteer)
                    <tr>
                        <td style="width:140px; text-align: center">
                        <div class="btn btn-sm btn-default"><i class="icon-trash glyphicon glyphicon-eye-open text-primary"></i></div>
                        <div class="btn btn-sm btn-default" ><i class="icon-trash glyphicon glyphicon-edit text-primary"></i></div>
                        <div class="btn btn-sm btn-default"><i class="icon-trash glyphicon glyphicon-trash text-danger"></i></div>
                    </td>
                        <td> {{ $volunteer["name"] }} </td>
                        <td> {{ $volunteer["date"] }} </td>
                        <td> {{$volunteer["status"]}} </td>
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