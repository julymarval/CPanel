@if(count($sponsors) > 0)
    <br>
    <h2>OUR SPONSORS</h2>
    <div class="row">
        @foreach ($sponsors as $sponsor)
            <div class="column col-sm-3 ">
                <img src="/images/sponsors/{{$sponsor["image"]}}" width="150" height="150">
            </div>
        @endforeach
    </div>
@endif
    <a href="#home" title="To Top">
        <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
    <p>Made By <a href="#" title="">Gabriel Rivas & July Marval</a></p>
