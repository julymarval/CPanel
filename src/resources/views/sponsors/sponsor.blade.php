

<div class="row">
    @foreach ($sponsors as $sponsor)
        <div class="column col-xs-4 col-md-3 ">
            {{$sponsor["name"] }}
        </div>
    @endforeach
</div>
<a href="#home" title="To Top">
    <span class="glyphicon glyphicon-chevron-up"></span>
</a>
<p>Made By <a href="#" title="">Gabriel Rivas</a></p>
