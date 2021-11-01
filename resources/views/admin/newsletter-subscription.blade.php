@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Newsletter Subscriptions</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="box box-info">
        <div class="box-body">
          <div class="col-md-12">
            <div class="pull-right form-group">
              <label class="control-label">Type &nbsp;</label>
              <select name="newsletter-type" onchange="toggleUser(this)">
                <option value="0">All</option>
                <option value="1">Customers</option>
                <option value="2">Guest</option>
              </select>
            </div>
          </div>
          <div class="col-md-12 table-responsive">
            <table id="Newsletter_Subscriptions" class="table no-margin table-small-padding">
              <thead>
                <tr>
                  <th>Email</th>
                  <th>Zip</th>
                  <th>Type</th>
                </tr>
              </thead>              
            </table>
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
  var newsletterUserType = 0;
  $(document).ready(function(){
    oTable = $('#Newsletter_Subscriptions').DataTable({
      processing: true,
      serverSide: true,
      "ajax": {
        "url": '{{route('get.subscriptions-list')}}',
        "type": "GET",
        "data": function (d) {
          d.type = window.newsletterUserType
        }
      },
      order: [['0','desc']],
      "drawCallback": function( settings ) {
        InitTooltip();
      },
      columns: [
          {data: '0', name: 'email'},
          {data: '1', name: 'zip'},
          {data: '2', name: 'type',searchable:false,sortable:false},
      ]
    });
  })
  function toggleUser(element){
    var user = $(element).val();
    newsletterUserType = user;
    console.log(newsletterUserType);
    console.log(user);
    oTable.draw(true);
  }
</script>
@stop