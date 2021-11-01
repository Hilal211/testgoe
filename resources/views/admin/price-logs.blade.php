@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Product Price</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <table id="PriceLogTable" class="table no-margin table-small-padding orderTable">
                <thead>
                  <tr>
                    <th class="col-md-2">Store</th>
                    <th class="col-md-6">Product</th>
                    <th class="col-md-1">Old Price</th>
                    <th class="col-md-1">New Price</th>
                    <th class="col-md-2">Edited on</th>
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
  var Todaydate = new Date();
  var date = $.datepicker.formatDate('yymmdd', Todaydate);
  console.log(date);
  $(document).ready(function(){
    oTable = $('#PriceLogTable').DataTable({
      processing: true,
      serverSide: true,
      dom: 'Bfrtip',
      ajax: 'price-log-data',
      order: [['0','desc']],
      /*buttons: [
        'csv', 'excel'
      ],*/
      buttons: [
          {
              extend: 'csv',
              text: 'Export as CSV',
              filename: date+'_Goecolo_products_pricelog'
          }
      ],
      "drawCallback": function( settings ) {
        InitTooltip();
      },
      columns: [
            {data: '0', name: 'store_details.storename'},
            {data: '1', name: 'products.product_name'},
            {data: '2', name: 'store_product_price_logs.old_price',searchable:false,sortable:false},
            {data: '3', name: 'store_product_price_logs.new_price',searchable:false,sortable:false},
            {data: '4', name: 'store_product_price_logs.created_at',searchable:false,sortable:false}
        ]
    });
  })
</script>
@stop