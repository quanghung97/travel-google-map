
@if (!auth()->user()->can('Access Admin'))
  @extends('layouts.app_user')
  @section('sidebar')
    @include('user.layouts.sidebar_user')
  @endsection

  @section('js') 
  <script>
        $('#listwp').DataTable();
  </script>
@endsection
@endif
