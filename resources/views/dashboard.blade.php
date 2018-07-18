@if (auth()->user()->can('Access Admin'))
  @include('admin.layouts.app_admin')
    @section('content')
      <div class="container">
          <div class="row">


              <div class="col-md-9">
                  <div class="card">
                      <div class="card-header">Dashboard</div>

                      <div class="card-body">
                      {{ Auth::user()->name }} application's dashboard.
                      </div>
                  </div>
              </div>
          </div>
      </div>
    @endsection
@else
  @include('user.layouts.app_user')
    @section('content')
        <div class="container">
            <div class="row">


                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">Dashboard</div>

                        <div class="card-body">
                        {{ Auth::user()->name }} application's dashboard.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection

@endif
