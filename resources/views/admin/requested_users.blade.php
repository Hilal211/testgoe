@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Service Requests</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <table id="Requested_Users" class="table no-margin table-small-padding orderTable">
                <thead>
                  <tr>
                    <th>Email</th>
                    <th>City</th>
                    <th>User Type</th>
                  </tr>
                </thead>              
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>
@stop
@section('custom_js')
<script>
  var oTable = ""
  $(document).ready(function(){
    oTable = $('#Requested_Users').DataTable({
      processing: true,
      serverSide: true,
      ajax: 'requested-users-data',
      order: [],
      "drawCallback": function( settings ) {
        InitTooltip();
      },
      columns: [
            {data: '0', name: 'email'},
            {data: '1', name: 'city'},
            {data: '2', name: 'user_type'}
        ]
    });
  })
</script>
@stop