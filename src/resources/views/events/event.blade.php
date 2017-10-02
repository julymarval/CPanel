
@extends('layouts.publicheader')
@section('content')
<!-- Container (Portfolio Section) -->
<div id="events" class="container-fluid text-center bg-grey">
    <br>
    <h2>EVENTS</h2><br>
    <h4>a description...</h4>
    <div id="exTab1" class="container">	
        <ul  class="nav nav-pills nav-justified">
              <li class="active">
                <a  href="#1a" data-toggle="tab">New</a>
              </li>
              <li><a href="#2a" data-toggle="tab">Past</a>
              </li>
            </ul>
              <div class="tab-content clearfix ">
                <div class="tab-pane active" id="1a">
                    <br>
                   
                    <div id="products" class="row list-group">
                        <div class="item  col-xs-4 col-lg-4">
                            <div class="thumbnail">
                                    <img src="http://tech.firstpost.com/wp-content/uploads/2014/09/Apple_iPhone6_Reuters.jpg" alt="" class="img-responsive">
                                    <div class="caption">
                                      <h4 class="pull-right">$700.99</h4>
                                      <h4>Mobile Product</h4>
                                      <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
                                    </div>
                                    <div class="space-ten"></div>
                                    <div class="btn-ground text-center">
                                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#product_view1"><i class="fa fa-search"></i> Details</button>
                                    </div>
                            </div>
                        </div>
                        
                      
                    </div>
                </div>
                <div class="tab-pane" id="2a">
                    <br>
                    <div id="products" class="row list-group">
                        <div class="item  col-xs-4 col-lg-4">
                            <div class="thumbnail">
            <img src="http://tech.firstpost.com/wp-content/uploads/2014/09/Apple_iPhone6_Reuters.jpg" alt="" class="img-responsive">
            <div class="caption">
              <h4 class="pull-right">$700.99</h4>
              <h4>Mobile Product</h4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</p>
            </div>
            <div class="space-ten"></div>
            <div class="btn-ground text-center">
                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#product_view2"><i class="fa fa-search"></i> Details</button>
            </div>
           
                        </div>
                        
                      
                    </div>              </div>
                
              </div>
          </div>
         
    
  </div>

  <div class="modal fade product_view" id="product_view1">
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
    <div class="modal fade product_view" id="product_view2">
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
                                <h3 class="cost"><span class="glyphicon glyphicon-usd"></span> 95.00</h3>
                            
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
    @include('includes.publicfooter')
  </footer>
  
  <script type="text/javascript" src="{{asset('js/magic.js')}}"></script>
  
  </body>
  </html>
@endsection