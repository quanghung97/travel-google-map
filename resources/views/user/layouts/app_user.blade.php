
  @extends('layouts.app_user')
  @section('sidebar')
    @include('user.layouts.sidebar_user')
  @endsection
  @section('js')
            <script type="text/javascript">
                  $(document).ready(function() {
                      $('#datatable').DataTable();
                          $('.form_datetime').datetimepicker({
                            widgetPositioning:{
                                          horizontal: 'auto',
                                          vertical: 'bottom'
                                      },
                                      format:'YYYY-MM-DD HH:mm:00'
                          });


                      });
                  </script>
@endsection


