@extends('player.layouts.app_user')

@section('content')
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">

                        <br/>
                        <br/>
                        @if (session()->has('success'))
                            <ul class="alert alert-success">

                                    <li>{{ session()->get('success') }}</li>

                            </ul>
                        @endif
                        <div class="clearfix"></div>

                        <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                        <div class="x_title">
                          <h2>Show Table To Bet! <small>Sessions</small></h2>
                          <ul class="nav navbar-right panel_toolbox">
                            <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                            </li>
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                              <ul class="dropdown-menu" role="menu">
                                <li><a href="#">Settings 1</a>
                                </li>
                                <li><a href="#">Settings 2</a>
                                </li>
                              </ul>
                            </li>
                            <li><a class="close-link"><i class="fa fa-close"></i></a>
                            </li>
                          </ul>
                          <div class="clearfix"></div>
                        </div>
                        <div class="table-responsive">
                            <table id="datatable" class="table table-borderless">
                                <thead>
                                    <tr>
                                        <th>#</th><th>Team 1 vs Team 2</th><th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($match as $item)
                                    <tr>
                                        <td>{{ $loop->iteration or $item->id }}</td>
                                        <td>{{ $item->team1->name }} vs {{ $item->team2->name }}</td>
                                        <td>
                                            <a href="{{ url('/player/football-bet/' . $item->id) }}" title="View Football_bet"><button class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> Go BET</button></a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                      </div>
                      </div>
                      </div>
                      </div>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
  <script type="text/javascript">
    $(document).ready( function () {
        $('#datatable').DataTable();
      } );
        </script>
@endsection
