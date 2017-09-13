@extends('layouts.adminheader')
@section('content')
      <div class="container">
      <div class="row vertical-offset-100">
        <div class="col-md-4 col-md-offset-4">
          <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Register an user</h3>
           </div>
            <div class="panel-body">
            <form id="check" method="POST" enctype="application/x-www-form-urlencoded" action="{{route('shows.store')}}">
  
                      <fieldset>
                      <div class="form-group">
                    <input class="form-control" placeholder="Name" name="name" type="text">
                </div>
                  <div class="form-group">
                    <input class="form-control" placeholder="schedule" name="schedule" type="text" >
                </div>
                <div class="form-group">
                    <input class="form-control" placeholder="description" name="description" type="text" >
                </div>
               
                <input class="btn btn-lg btn-success btn-block" type="submit" value="Save">
              </fieldset>
                </form>
            </div>
        </div>
      </div>
    </div>
  </div>

    </body>
</html>


@endsection