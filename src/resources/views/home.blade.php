@extends('layouts.publicheader')
@section('content')

<div id="myCarousel" class="carousel slide" data-ride="carousel">
    <!-- Indicators -->
    <ol class="carousel-indicators">
      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
      <li data-target="#myCarousel" data-slide-to="1"></li>
    </ol>

    <!-- Wrapper for slides -->
    <div class="carousel-inner" role="listbox">
      <div class="item active">
        <img src="{{asset('/images/p1.JPG')}}"  width="1200" height="700">     
      </div>

      <div class="item">
        <img src="{{asset('/images/p2.JPG')}}" width="1200" height="700">
      
      </div>
    </div>
</div>


<div class="text-center covertext">
  <h1>RADIO SABOR LATINO 93.5 FM</h1>
  <h3>SIEMPRE CONTIGO</h3>
  <br>
  <br>
  <p>
    <a  id="page-help" href="http://streamdb6web.securenetsystems.net/v5/WSBL" onclick="window.open(this.href, 'popupwindow', 'width=500,height=300'); return false;" class="btn btn-primary btn-round-lg btn-lg"><span class="glyphicon glyphicon-play"></span>LIVE</a>
    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RGJJ7RX543MDQ"target="_blank" class="btn btn-default btn-round-lg btn-lg"> <span class="glyphicon glyphicon-heart-empty red"></span> DONATE</a>
  </p>
<br>

</div>


<div id="team" class="container-fluid text-center bg-grey">
    <h2>TEAM</h2>
    <div class="row row-centered">
        
                <div class="col-md-4 team">
                    <img src="http://www.radiosaborlatino.com/wp-content/uploads/2016/07/13709631_10100201065576745_590823939_o-1-e1470878796336.jpg" alt="Mike">
                    <div>
                      <h2>Jose Flores</h2>
                      <p class="title">CEO &amp; Founder</p>
                      <p>Some text that describes me lorem ipsum ipsum lorem.</p>
                    </div>
                </div>
              
                <div class="col-md-4 team">
                    <img src="http://www.radiosaborlatino.com/wp-content/uploads/2016/07/13692413_10100201065566765_1983183408_o-e1470879004167.jpg" alt="Mike">
                    <div>
                      <h2>Mike Ross</h2>
                      <p class="title">Art Director</p>
                      <p>Some text that describes me lorem ipsum ipsum lorem.</p>
                    </div>
                </div>
              
                <div class="col-md-4 team">
                    <img src="http://www.radiosaborlatino.com/wp-content/uploads/2016/07/13709631_10100201065576745_590823939_o-1-e1470878796336.jpg" alt="Mike">
                    <div>
                      <h2>John Doe</h2>
                      <p class="title">Designer</p>
                      <p>Some text that describes me lorem ipsum ipsum lorem.</p>
                    </div>
                </div>
        
        
            </div>
  </div>

<div id="shows" class="container-fluid text-center">
  <h2>SHOWS</h2>
  <h4>What we offer</h4>
  <br>
  <div class="text-center showresult">
    <h1>Select a show</h1>
  </div>
  <br>  
  <div class="row">
    @foreach ($shows as $show) 
        <div class="column col-sm-4 ">          
          <div class="card  show1">
            <h4>{{$show["name"]}}</h4>
            <br>
          </div>
        </div>
    @endforeach
  </div>


<div id="contact" class="container-fluid bg-grey">
  <h2 class="text-center">CONTACT</h2>
  <div class="row">
    <div class="col-sm-5">
      <p>Contact us and we'll get back to you within 24 hours.</p>
      <p><span class="glyphicon glyphicon-map-marker"></span> 2015 W Western Ave, South Bend, IN 46619</p>
      <p><span class="glyphicon glyphicon-phone"></span> +1 574232 3212</p>
      <p><span class="glyphicon glyphicon-envelope"></span> myemail@something.com</p>
    </div>
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
  <a href="#home" title="To Top">
    <span class="glyphicon glyphicon-chevron-up"></span>
  </a>
  <p>Made By <a href="#" title="">Gabriel Rivas</a></p>
</footer>

<script type="text/javascript" src="{{asset('js/magic.js')}}"></script>

</body>
</html>
@endsection