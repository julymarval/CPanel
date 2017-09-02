<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Login</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
    </head>
    <body>
      <form method="POST" enctype="application/x-www-form-urlencoded" action="http://localhost:8000/admin/authenticate">
        <div class="container">
          <label><b>Username</b></label>
          <input type="text" placeholder="Enter Username" name="email" required>

          <label><b>Password</b></label>
          <input type="password" placeholder="Enter Password" name="password" required>

          <button type="submit">Login</button>
        </div>
      </form>
    </body>
</html>