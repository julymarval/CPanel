
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
  <style>
            html, body {
                background-color: #f4511e;
                color: #636b6f;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
            .vertical-offset-100{
                padding-top:100px;
            }
         .brand {
            padding: 0px;
            height: 80px;
             text-align: center;
          }
        .brand>img {
            height: 100%;
            padding: 15px;
            width: auto;
          }
  </style>

</head>
        
    <body>
      <div class="container">
      <div class="row vertical-offset-100">
        <div class="col-md-4 col-md-offset-4">
            
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Please sign in</h3>
           </div>
            <div class="panel-body">
            <form method="POST" enctype="application/x-www-form-urlencoded" action="{{route('authenticate.auth')}}">
  
                      <fieldset>
                  <div class="form-group">
                    <input class="form-control" placeholder="E-mail" name="email" type="text">
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Password" name="password" type="password" value="">
                </div>
                <input class="btn btn-lg btn-success btn-block" type="submit" value="Login">
              </fieldset>
                </form>
            </div>
        </div>
            <br>
            <br>
            <div class="brand">
            <img src="{{asset('/images/logo3.png')}}" >
            </div>
      </div>
    </div>
  </div>
    </body>
</html>


