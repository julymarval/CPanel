@extends('layouts.publicheader')
@section('content')



<header style="background-image: url({{asset('/images/p2.JPG')}});
        padding-top: 500px;
        padding-bottom: 50px;
        position: relative;
        width: 100%;
        min-height: auto;
        text-align: center;
        color: #fff;
        background-position: center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;">

<div class="header-content">
    <div class="header-content-inner">
        <h1 id="homeHeading">Radio Sabor Latino 93.5 FM</h1>
        <hr>
        <p>Contigo Siempre!</p>

            <a  id="page-help" href="http://streamdb6web.securenetsystems.net/v5/WSBL" onclick="window.open(this.href, 'popupwindow', 'width=500,height=300'); return false;" class="btn btn-primary btn-xl js-scroll-trigger">Play <span class="glyphicon glyphicon-play"></span></a>
            <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RGJJ7RX543MDQ"target="_blank" class="btn btn-primary btn-xl js-scroll-trigger">DONATE <span class="glyphicon glyphicon-heart-empty"></span></a>
        <br>
    </div>
</div>
</header>






<div id="shows" class="container-fluid text-center">
  <h2>SHOWS</h2>
  <h4>What we offer</h4>
  <div class="text-center showresult">
  <br>

    <h1>Select a show</h1>
  </div>
  @if(count($shows) > 0)
 
  <ul class="bxslider">
  @foreach ($shows as $show)
  <li> <img class ="card-image img-responsive center-block" src="/images/shows/{{$show["image"]}}"  title="{{$show["schedule"]}} <br> {{$show["description"]}}" style="max-width: 100%; height: 600px;" ></li>

  @endforeach
</ul>

<div id="bx-pager">
  @foreach ($shows as $key => $show)
  <a data-slide-index="{{$key}}" href=""><img src="/images/shows/{{$show["image"]}}" width="100" height="75"/></a>
  @endforeach
</div>
@endif
  <br>  

  {{$shows}}
</div>
<div id="contact" class="container-fluid bg-grey">
  <h2 class="text-center">CONTACT</h2>
  <div class="row">
    <div class="col-sm-5">
      <p>Contact us and we'll get back to you within 24 hours.</p>
      <p><span class="glyphicon glyphicon-map-marker"></span> 2015 W Western Ave, South Bend, IN 46619</p>
      <p><span class="glyphicon glyphicon-phone"></span> +1 574232 3212</p>
    </div>
    <form id="check" method="POST" enctype="multipart/form-data" action="{{route('contact')}}">  
    <div class="col-sm-7 slideanim">
      <div class="row">
        <div class="col-sm-6 form-group">
          <input class="form-control" id="name" name="name" placeholder="Name" type="text" required>
        </div>
        <div class="col-sm-6 form-group">
          <input class="form-control" id="email" name="email" placeholder="Email" type="email" required>
        </div>
      </div>
      <textarea class="form-control" id="comments" name="comments" placeholder="Comment" rows="5"></textarea><br>
      <div class="row">
        <div class="col-sm-12 form-group">
          <button class="btn btn-default pull-right" type="submit">Send</button>
        </div>
      </div>
    </div>
    </form>
  </div>
</div>
<div id="map"></div>
<script>

    function initMap() {
      var map = new google.maps.Map(document.getElementById('map'), {
        center: {lat: 41.672139, lng: -86.2813008},

        zoom: 15
      });

      var infowindow = new google.maps.InfoWindow();
      var service = new google.maps.places.PlacesService(map);

      service.getDetails({
        placeId: 'ChIJG7L1XugyEYgRC_S8rBAJsn8'
      }, function(place, status) {
        if (status === google.maps.places.PlacesServiceStatus.OK) {
          var marker = new google.maps.Marker({
            map: map,
            position: place.geometry.location
          });
          google.maps.event.addListener(marker, 'click', function() {
            infowindow.setContent('<div><strong>' + place.name + '</strong><br>' +
              'Place ID: ' + place.place_id + '<br>' +
              place.formatted_address + '</div>');
            infowindow.open(map, this);
          });
        }
      });
    }
  </script>
<script async defer
src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCHyMG1u6cbwDVjX3nT974k73SpMOaC8hQ&libraries=places&callback=initMap">
</script>

    <footer class="container-fluid text-center">
        @include('includes.publicfooter')
    </footer>

    <script type="text/javascript" src="{{asset('js/magic.js')}}"></script>

    </body>
    </html>

@endsection


