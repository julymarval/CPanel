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
            <br>
            <a href={{route('admin.volunteers')}}>
                <span class="glyphicon glyphicon-triangle-left">Back</span>
            </a>
            <br>
            <div class="col-md-12 col-sm-12 col-xs-12 gutter">
                <div class="row vertical-offset-100">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title" style="text-align: center">{{$volunteer -> name}}</h3>
                        </div>
                        <div class="panel-body">
                            <form id="check" method="POST" enctype="multipart/form-data" action="{{route('volunteers.update', $volunteer -> id)}}">
                                <fieldset>
                                    <div class="form-group">Name
                                        <input class="form-control" placeholder={{$volunteer['name']}} name="name" type="text">
                                    </div>
                                    <div class="form-group">Phone
                                        <input class="form-control" name="phone" placeholder={{$volunteer['phone']}}>
                                    </div>
                                    <div class="form-group">
                                        <label for="sel1">Select a Status:</label>
                                        <select class="form-control"  name="status">
                                            <option disabled selected value> -- select an option -- </option>
                                            <option value="Activo">Activo</option>
                                            <option value="Inactivo">Inactivo</option>
                                        </select>
                                    </div> 
                                    <div class="form-group">Description
                                        <textarea class="form-control" placeholder={{$volunteer['description']}} name="description"></textarea>
                                    </div>
                                    <div class="form-group has-feedback">Image
                                        <input type="file" id="fileupload" name="photos[]" data-url="/uploadvolunteer" single />
                                    </div>
                                    <br>
                                    <div id="files_list"></div>
                                        <p id="loading"></p>
                                    <input type="hidden" name="image_name" id="name" value="" />
                                    <br>
                                    <div class="form-group">
                                        {!! Form::label('shows', 'Shows') !!}
                                        {!! Form::select('show_id[]',$shows," ",
                                        ['shows' => 'id', 'class' => 'form-control select-show',
                                        'multiple',null]) !!}
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('events', 'Events') !!}
                                        {!! Form::select('event_id[]',$events,
                                        " ",['events' => 'id', 'class' => 'form-control select-event',
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
    <script type="text/javascript" src="{{asset('js/admin.js')}}"></script>
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
                    if ($('#name').val() != '') {
                        $('#name').val($('#name').val() + ',');
                    }
                    $('#name').val($('#name').val() + file.name);
                });
                $('#loading').text('');
            }
        });
        $(".select-show").chosen({
            placeholder_text_multiple: 'Click to select show',
        });
        $(".select-event").chosen({
            placeholder_text_multiple: 'Click to select events',
        });
    </script>
    <script>
        $('div.alert').not('.alert-important').delay(8000).fadeOut(350);
    </script> 
</body>
@endsection