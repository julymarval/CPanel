@extends('layouts.adminheader')
@section('content')
<div class="col-md-10 col-sm-11 display-table-cell v-align">
    @include('flash::message')
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
            <div class="col-md-12 col-sm-12 col-xs-12 gutter">
                <div class="container">
                    <a href={{route('admin.events')}}>
                        <span class="glyphicon glyphicon-triangle-left">Back</span>
                    </a>
                </div>
                <br>
                <div class="row vertical-offset-100">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title" style="text-align: center">{{$event -> name}}</h3>
                            </div>
                            <div class="panel-body">
                                <form id="check" method="POST" enctype="multipart/form-data" action="{{route('events.update', $event -> id)}}">
                                    <fieldset>
                                        <div class="form-group"> Name
                                            <input class="form-control" placeholder={{$event['name']}} name="name" type="text">
                                        </div>
                                        <div class="form-group">Date
                                            <input class="form-control" data-provide="datepicker" placeholder={{$event['date']}} data-date-format="yyyy-mm-dd" name="date">
                                        </div>
                                        <div class="form-group">Description<br> <!-- aqui abajo no se como hacer para que muestre el valor
                                                                                pero por aqui van los tiros, el problema es con las llaves
                                                                                que dan acceso a la variable que lo agarra como si cerrara el 
                                                                                form. Si se puede hacer algo asi con el input que teniamos
                                                                                antes mejor-->
                                            {{                          
                                            {!! Form::textarea('title',isset({{$event['description']}}) ? null : '',['class'=>'form-control', 'rows' => 4, 'cols' => 40]) !!}
                                            }}
                                        </div>
                                        <div class="form-group has-feedback"> Image
                                            <input type="file" id="fileupload" name="photos[]" data-url="/uploadevent" multiple />
                                        </div>
                                        <br />
                                        <div id="files_list"></div>
                                            <p id="loading"></p>
                                        <input type="hidden" name="file_ids" id="file_ids" value="" />
                                        <br>
                                        <div class="form-group">
                                            {!! Form::label('volunteers', 'Volunteers') !!}
                                            {!! Form::select('volunteer_id[]',$volunteers," ",
                                            ['volunteers' => 'id', 'class' => 'form-control select-tag',
                                            'multiple',null]) !!}
                                        </div>
                                        <div class="form-group">
                                            {!! Form::label('sponsors', 'Sponsors') !!}
                                            {!! Form::select('sponsor_id[]',$sponsors," ",
                                            ['sponsors' => 'id', 'class' => 'form-control select-sponsor',
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
    <script type="text/javascript" src="{{asset('js/admin.js')}}"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.7.1/js/bootstrap-datepicker.min.js">
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.2/chosen.jquery.min.js"></script>                       
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/vendor/jquery.ui.widget.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/jquery.iframe-transport.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/blueimp-file-upload/9.19.1/js/jquery.fileupload.min.js"></script>

    <script>
        $('#fileupload').fileupload({
            dataType: 'json',
            add: function (e, data) {
                $('#loading').text('Uploading...');
                data.submit();
            },
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    $('<p/>').html(file.name + ' (' + file.size + ' KB)').appendTo($('#files_list'));
                    if ($('#file_ids').val() != '') {
                        $('#file_ids').val($('#file_ids').val() + ',');
                    }
                    $('#file_ids').val($('#file_ids').val() + file.fileID);
                });
                $('#loading').text('');
            }
        });
        $(".select-tag").chosen({
            placeholder_text_multiple: 'Click to select volunteers',
        });
        $(".select-sponsor").chosen({
            placeholder_text_multiple: 'Click to select sponsors',
        });
    </script>   

    <script>
        $('div.alert').not('.alert-important').delay(8000).fadeOut(350);
    </script>
</body>
@endsection