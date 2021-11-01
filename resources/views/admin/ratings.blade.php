@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Ratings</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <table id="All_Rating" class="table no-margin table-small-padding orderTable">
                <thead>
                  <tr>
                    <th class="col-md-1">Order#</th>
                    <th class="col-md-1">Store</th>
                    <th class="col-md-1">Customer</th>
                    <th class="col-md-3">Rating</th>
                    <th class="col-md-5">Comments</th>
                    <th class="col-md-1">Rating Date</th>
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
    oTable = $('#All_Rating').DataTable({
      processing: true,
      serverSide: true,
      ajax: 'admin-rating-data',
      order: [['0','desc']],
      "drawCallback": function( settings ) {
        InitTooltip();
        InitRate();
      },
      columns: [
            {data: '0', name: 'o.order_number'},
            {data: '1', name: 's.storename'},
            {data: '2', name: 'u.firstname'},
            {data: '3', name: 'ratings.rating'},
            {data: '4', name: 'ratings.comments'},
            {data: '5', name: 'ratings.created_at'}
        ]
    });
  })

  function OpenInvoice(element){
    var Id = $(element).data('id');
    $.ajax({
      type: "POST",
      url: APP_URL+'/store/invoice/'+Id,
      data: "id="+Id,
      dataType: "json",
      success: function(res) {
        $('#InvoiceModal').remove();
        $('body').append(res.InvoiceHTML);
        $('#InvoiceModal').modal('show');
      }
    });
  }
</script>
@stop