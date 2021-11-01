@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Orders</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <table id="All_OrderTable" class="table no-margin table-small-padding orderTable">
                <thead>
                  <tr>
                    <th>Order#</th>
                    <th>Customer</th>
                    <th>Items</th>
                    <th>Store</th>
                    <th>Order Date</th>
                    <th>Total</th>
                    <th>Commision</th>
                    <th>Status</th>
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
    oTable = $('#All_OrderTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: 'order-data',
      order: [['0','desc']],
      "drawCallback": function( settings ) {
        InitTooltip();
      },
      columns: [
            {data: '0', name: 'orders.order_number'},
            {data: '1', name: 'users.username'},
            {data: '2', name: 'total_items',searchable:false},
            {data: '3', name: 'store_details.storename'},
            {data: '4', name: 'orders.created_at'},
            {data: '5', name: 'orders.grand_total'},
            {data: '6', name: 'payments.comm'},
            {data: '7', name: 'orders.status'}
        ]
    });
  })

  function OpenInvoice(element){
    var Id = $(element).data('id');
    $.ajax({
      type: "POST",
      url: APP_URL+'/en/store/invoice/'+Id,
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