
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
  <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.12.0/jquery.validate.js"></script>
  <style>
            html, body {
                background-color: #BBBBBB;
                font-family: 'Raleway', sans-serif;
                font-weight: 100;
                height: 100vh;
                margin: 0;
            }
            .vertical-offset-100{
                padding-top:100px;
            }
  </style>

</head>
        
    <body>
      <div class="container">
      <div class="row vertical-offset-100">
        <div class="col-md-4 col-md-offset-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Register an user</h3>
           </div>
            <div class="panel-body">
            <form id="check" method="POST" enctype="application/x-www-form-urlencoded" action="{{route('users.store')}}">
  
                      <fieldset>
                      <div class="form-group">
                    <input class="form-control" placeholder="Name" name="name" type="text" required>
                </div>
                  <div class="form-group">
                    <input class="form-control" placeholder="E-mail" name="email" type="text" required>
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Password" name="password" type="password" value="" id="password" required>
                </div>
                <div class="form-group">
                  <input class="form-control" placeholder="Confirm Password" name="repassword" type="password" value="" id="repassword" required>
                </div>
                <input class="btn btn-lg btn-success btn-block" type="submit" value="Register">
              </fieldset>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>
  <script>
      $(document).ready(function(){
       $("#check").validate({
        rules: {
         password: {
             required: true,
             minlength: 8,
             maxlength: 10
         },
         repassword: {
             equalTo: "#password"
         }
     },
     messages: {
         password: {
             required: "the password is required"
         }
     }
    });
    })
  </script>

</body>
</html>


