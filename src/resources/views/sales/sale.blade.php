@extends('layouts.publicheader')
@section('content')

    <div id="tianguis" class="container-fluid text-center bg-grey">
        <br><h2>TIANGUIS</h2><br>
        <h4>"what the community is selling"</h4>
        <div class="row">
            <table class="table table-striped">
                <thead >
                <tr>
                    <th  class="col-xs-2" style="text-align: center">Name</th>
                    <th  class="col-xs-1" style="text-align: center">Phone</th>
                    <th  class="col-xs-1" style="text-align: center">Price</th>
                    <th  class="col-xs-8" style="text-align: center">Description</th>
                </tr>
                </thead>

                <tbody>
                @if(count($sales) > 0)
                    @foreach ($sales as $sale)
                        <tr>
                            </td>
                            <td> {{$sale["name"] }} </td>
                            <td> {{$sale["phone"] }} </td>
                            <td> ${{$sale["price"] }}</td>
                            <td> {{$sale["description"] }} </td>
                        </tr>
                    @endforeach
                @endif
                </tbody>
            </table>
            {{$sales}}
        </div>
    </div>


    <footer class="container-fluid text-center">
        @include('includes.publicfooter')
    </footer>
    </div>
    <script type="text/javascript" src="{{asset('js/magic.js')}}"></script>

    </body>
    </html>
@endsection