
<!DOCTYPE html>
<html lang="en">
<head>
  <title>RADIO SABOR LATINO 93.5 FM</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

  
  <link rel="stylesheet" type="text/css" href="{{asset('/css/style.css')}}">
  </head>
<body id="myPage" data-spy="scroll" data-target=".navbar" data-offset="60">

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>                        
      </button>
      <a class="navbar-brand" href="index.html">
        <img src="logo3.png" >
      </a>
    </div>
    <div class="collapse navbar-collapse" id="myNavbar">
      <ul class="nav navbar-nav navbar-right">
        <li><a href="{{ url('/') }}">RADIO</a></li>
        <li><a href="{{ url('/events') }}">EVENTS</a></li>
        <li><a href="#tianguis">TIANGUIS</a></li>
        <li><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=RGJJ7RX543MDQ"target="_blank">DONA</a></li>
      </ul>
    </div>
  </div>
</nav>


<div id="tianguis" class="container-fluid text-center bg-grey"> 
    <br><h2>TIANGUIS</h2><br>
    <h4>What we are selling</h4>
  <div class="row">
    <div class="col-md-4">
          <div class="thumbnail">
            <img src="http://tech.firstpost.com/wp-content/uploads/2014/09/Apple_iPhone6_Reuters.jpg" alt="" class="img-responsive">
            <div class="caption">
              <h4 class="pull-right">$700.99</h4>
              <h4>Mobile Product</h4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            </div>
            <div class="space-ten"></div>
            <div class="btn-ground text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#product_view"><i class="fa fa-search"></i> Details</button>
            </div>
           
          </div>
        </div>        
  </div>
<div class="modal fade product_view" id="product_view">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <a href="#" data-dismiss="modal" class="class pull-right"><span class="glyphicon glyphicon-remove"></span></a>
            <h3 class="modal-title">Product Name</h3>
        </div>
        <div class="modal-body">
            <div class="row">
                <div id="myCarousel" class="carousel  col-md-6 product_img" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                      <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                      <li data-target="#myCarousel" data-slide-to="1"></li>
                      <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>
                
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner">
                      <div class="item active">
                        <img src="http://img.bbystatic.com/BestBuy_US/images/products/5613/5613060_sd.jpg" alt="Los Angeles" style="width:100%;">
                      </div>
                
                      <div class="item">
                        <img src="http://tech.firstpost.com/wp-content/uploads/2014/09/Apple_iPhone6_Reuters.jpg" alt="Chicago" style="width:100%;">
                      </div>
                    
                      <div class="item">
                        <img src="http://img.bbystatic.com/BestBuy_US/images/products/5613/5613060_sd.jpg" alt="New york" style="width:100%;">
                      </div>
                    </div>
                
                    <!-- Left and right controls -->
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
                    
                    <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.Lorem Ipsum is simply dummy text of the printing and typesetting industry.</p>
                    <h3 class="cost"><span class="glyphicon glyphicon-usd"></span> 75.00</h3>
                
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
    </div>
</div>            


</div>
</div>

<footer class="container-fluid text-center">
    <a href="#tianguis" title="To Top">
      <span class="glyphicon glyphicon-chevron-up"></span>
    </a>
    <p>Made By <a href="#" title="">Gabriel Rivas</a></p>
  </footer>
  
  <script type="text/javascript" src="{{asset('js/magic.js')}}"></script>
  
  </body>
  </html>