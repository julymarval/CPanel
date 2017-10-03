@extends('layouts.publicheader')
@section('content')
    <!-- Container (Portfolio Section) -->
    <div id="events" class="container-fluid text-center bg-grey">
        <br>
        <h2>EVENTS</h2><br>
        <h4>...</h4>
        <div id="exTab1" class="container">
            <ul class="nav nav-pills nav-justified">
                <li class="active">
                    <a href="#1a" data-toggle="tab">New</a>
                </li>
                <li><a href="#2a" data-toggle="tab">Past</a>
                </li>
            </ul>
            <div class="tab-content clearfix ">
                <div class="tab-pane active" id="1a">
                    <br>
                    <div id="products" class="row list-group">
                        @if(count($futureevents) > 0)
                        <?php $j = 0; ?>
                        <?php $i = 0; ?>
                            <div class="item  col-xs-4 col-lg-4">
                                <div class="thumbnail">
                                @if(count($images[0]) > 0)
                                    <img src="/images/events/{{$images[$i] -> name}}" class="img-responsive">
                                @endif
                                        <div class="caption">
                                            <h4 class="pull-right">{{$futureevents[$j]->date }}</h4>
                                            <h4>{{$futureevents[$j]->name }}</h4>
                                        </div>
                                        <div class="space-ten"></div>
                                            <div class="btn-ground text-center">
                                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                                    data-target="#product_{{$futureevents[$j]->id }}"><i class="fa fa-search"></i> Details
                                                </button>
                                            </div>
                                        </div>
                                <?php $i++; ?>
                            </div>
                            <?php $j++; ?>
                            
                            @endif
                    </div>
                    {{$futureevents}}
                </div>
                <div class="tab-pane" id="2a">
                    <br>
                    <div id="products" class="row list-group">
                        @if(count($pastevents) > 0)
                        <?php $j = 0; ?>
                        <?php $i = 0; ?>
                        <div class="item  col-xs-4 col-lg-4">
                            <div class="thumbnail">
                                @if(count($images[0]) > 0)
                                    <img src="/images/events/{{$images[$i] -> name}}" class="img-responsive">
                                @endif
                                    <div class="caption">
                                        <h4 class="pull-right">{{$pastevents[$j]->date }}</h4>
                                        <h4>{{$pastevents[$j]->name }}</h4>
                                    </div>
                                    <div class="space-ten"></div>
                                    <div class="btn-ground text-center">
                                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                                data-target="#product_{{$pastevents[$j]->id }}"><i class="fa fa-search"></i> Details
                                        </button>
                                    </div>
                            </div>
                            <?php $i++; ?>
                        </div>
                        <?php $j++; ?>
                        
                            @endif
                    </div>
                    {{$pastevents}}
                </div>
            </div>


        </div>
        @if(count($futureevents) > 0)
            <?php $k = 0; ?>
            <div class="modal fade product_view" id="product_{{$futureevents[$k]->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <a href="#" data-dismiss="modal" class="class pull-right"><span
                                        class="glyphicon glyphicon-remove"></span></a>
                            <h3 class="modal-title">{{$futureevents[$k]->name }}</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div id="myCarousel" class="carousel col-md-6 product_img" data-ride="carousel">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        @foreach($imgs as $i => $image)
                                            @if ($i ==0)
                                                <li data-target="#carouselExampleIndicators"
                                                    data-slide-to="{{ $loop->index }}"
                                                    class="active"></li>
                                            @else
                                                <li data-target="#carouselExampleIndicators"
                                                    data-slide-to="{{ $loop->index }}"></li>
                                            @endif

                                        @endforeach
                                    </ol>

                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        @foreach($imgs as $i => $image)
                                            @if($image -> event_id == $futureevents[$k] -> id)
                                                @if ($i == 0)
                                                    <div class="active item">
                                                        <img class="d-block img-fluid" src="/images/events/{{$image -> name}}"
                                                             alt="{{ $futureevents[$k]->name }}" style="width:100%;">
                                                    </div>
                                                @else
                                                    <div class="item">
                                                        <img class="d-block img-fluid" src="/images/events/{{$image -> name}}"
                                                             alt="{{ $futureevents[$k]->name }}" style="width:100%;">
                                                    </div>
                                                @endif
                                            @endif

                                        @endforeach
                                    </div>
                                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                                <div class="col-md-6 product_content">
                                    <p>
                                        {{$futureevents[$k]->description}}
                                    </p>
                                    <br>
                                    <h3 class="cost"><span class="glyphicon glyphicon-calendar"></span> {{$futureevents[$k]->date }}</h3>

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php $k++; ?>
        @endif

        @if(count($pastevents) > 0)
            <?php $m = 0; ?>
            <div class="modal fade product_view" id="product_{{$pastevents[$k]->id }}">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <a href="#" data-dismiss="modal" class="class pull-right"><span
                                        class="glyphicon glyphicon-remove"></span></a>
                            <h3 class="modal-title">{{$pastevents[$k]->name }}</h3>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div id="myCarousel" class="carousel col-md-6 product_img" data-ride="carousel">
                                    <!-- Indicators -->
                                    <ol class="carousel-indicators">
                                        @foreach($imgs as $i => $image)
                                            @if ($i ==0)
                                                <li data-target="#carouselExampleIndicators"
                                                    data-slide-to="{{ $loop->index }}"
                                                    class="active"></li>
                                            @else
                                                <li data-target="#carouselExampleIndicators"
                                                    data-slide-to="{{ $loop->index }}"></li>
                                            @endif

                                        @endforeach
                                    </ol>

                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        @foreach($imgs as $i => $image)
                                            @if($image -> event_id == $pastevents[$k] -> id)
                                                @if ($i == 0)
                                                    <div class="active item">
                                                        <img class="d-block img-fluid" src="/images/events/{{$image -> name}}"
                                                             alt="{{ $pastevents[$k]->name }}" style="width:100%;">
                                                    </div>
                                                @else
                                                    <div class="item">
                                                        <img class="d-block img-fluid" src="/images/events/{{$image -> name}}"
                                                             alt="{{ $pastevents[$k]->name }}" style="width:100%;">
                                                    </div>
                                                @endif
                                            @endif

                                        @endforeach
                                    </div>
                                    <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                        <span class="glyphicon glyphicon-chevron-left"></span>
                                        <span class="sr-only">Previous</span>
                                    </a>
                                    <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                        <span class="glyphicon glyphicon-chevron-right"></span>
                                        <span class="sr-only">Next</span>
                                    </a>
                                </div>
                                <div class="col-md-6 product_content">
                                    <p>
                                        {{$pastevents[$k]->description}}
                                    </p>
                                    <br>
                                    <h3 class="cost"><span class="glyphicon glyphicon-calendar"></span> {{$pastevents[$k]->date }}</h3>

                                </div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php $k++; ?>
        @endif
            </div>
    <footer class="container-fluid text-center">
        @include('includes.publicfooter')
    </footer>

    <script type="text/javascript" src="{{asset('js/magic.js')}}"></script>

    </body>
    </html>
@endsection