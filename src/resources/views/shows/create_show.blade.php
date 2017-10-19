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
            <div class="col-md-12 col-sm-12 col-xs-12 gutter">
                <div class="container">
                    <a href={{route('admin.shows')}}>
                        <span class="glyphicon glyphicon-triangle-left">Back</span>
                    </a>
                </div>
                <br>
                <div class="row vertical-offset-100">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title" style="text-align: center">Add new show</h3>
                            </div>
                            <div class="panel-body">
                                <form id="check" data-toggle="validator" method="POST" enctype="multipart/form-data" action="{{route('shows.store')}}">
                                    <fieldset>
                                        <div class="form-group has-feedback">
                                            <input class="form-control" placeholder="Name" name="name" type="text" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <input class="form-control has-feedback" placeholder="schedule" name="schedule" type="text" required>
                                            <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
                                        </div>              
                                        <div class="form-group">
                                            <textarea class="form-control" placeholder="description" name="description"></textarea>
                                        </div>
                                        <div class="form-group has-feedback">
                                            <input type="file" id="fileupload" name="photos[]" data-url="/uploadshow" multiple />
                                        </div>
                                        <br />
                                        <div id="files_list"></div>
                                            <p id="loading"></p>
                                        <input type="hidden" name="image" id="name" value="" />
                                        <br>
                                        <div class="form-group">
                                            {!! Form::label('volunteers', 'Volunteers') !!}
                                            {!! Form::select('volunteer_id[]',$volunteers," ",
                                            ['volunteers' => 'id', 'class' => 'form-control select-tag',
                                            'multiple']) !!}
                                        </div>
                                        <br>
                                        <input class="btn btn-lg btn-success btn-block" type="submit" value="Save">
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
        $(".select-tag").chosen({
            placeholder_text_multiple: 'Click to select volunteers',
        });
    </script>
    <script>
        $('div.alert').not('.alert-important').delay(8000).fadeOut(350);
    </script>
</body>
@endsection