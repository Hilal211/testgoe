@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Pickups</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-6">
        <div class="box box-solid box-danger">
          <div class="box-header with-border">
            <i class="fa fa-thumbs-o-up"></i>
            <h3 class="box-title">Ready To Pickup</h3>
          </div>
          <div class="box-body">
            <table id="Ready_To_OrderTable" data-type="6" class="table no-margin table-small-padding orderTable">
              <thead>
                <tr>
                  <th>Order#</th>
                  <th>Items</th>
                  <th>Store</th>
                  <th>Ship To</th>
                  <th>Total</th>
                  <th>Action</th>
                </tr>
              </thead>         
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="box box-solid box-warning">
          <div class="box-header with-border">
            <i class="fa fa-shopping-cart"></i>
            <h3 class="box-title">Shipped Orders</h3>
            <div class="box-tools pull-right">
              <span class="badge bg-aqua order_price description"></span>
            </div>
          </div>
          <div class="box-body">
            <table id="Shipped_OrderTable" data-type="7" class="table no-margin table-small-padding orderTable">
              <thead>
                <tr>
                  <th>Order#</th>
                  <th>Items</th>
                  <th>Store</th>
                  <th>Ship To</th>
                  <th>Total</th>
                  <th>Actions</th>
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
var Ready_To_OrderTable = "";
var Shipped_OrderTable = "";
$(document).ready(function(){
  $('.orderTable').each(function(i, row) {
    var Id = $(this).attr('id'),
        data = $(this).data('type');
    window[Id] = $(this).DataTable({
      "processing": true,
      "serverSide": true,
      "autoWidth": false,
      "ajax": {
        "url": '{{route('admin.order-data')}}',
        "type": "POST",
        "data": function (d) {
          d.key="value",
          d.status = 8
        }
      },
      "drawCallback": function( settings ) {
          InitTooltip();
      },
      "fnRowCallback": function(nRow,aData,iDisplayIndex,iDisplayIndexFull) {
          /*var JsonData = $(nRow).find('button.change-order-status').data('json');
          $(nRow).removeClass('blink_me');
          if(JsonData !== undefined){
            if(JsonData.blink !== undefined){
              $(nRow).addClass('blink_me');
            }
          }*/
      },
      "dom": 'rtp',
      'iDisplayLength': 10,
      "ordering": false
    });
  });

  setInterval(function(){
    Ready_To_OrderTable.draw(true);
    Shipped_OrderTable.draw(true);
  },120000);
});

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

function change_status(Element,event){
  var Row = $(Element).parents('tr');
  var JsonData = $(Element).data('json');
  var NewStatus = JsonData.new_status;
  var OldStatus = JsonData.old_status;
  var OrderId = JsonData.id;
  $.ajax({
    type: "POST",
    url: '{{route('admin.order-status-change')}}',
    data: JsonData,
    dataType: "json",    
    success: function (res) {
      JsonData.row = Row;
      ChangeUI(res,JsonData,'tables');
    }
  });
}
function ChangeUI(response,data,type){
  if(type=='tables'){
    if(data.new_status=='8'){
      Shipped_OrderTable.draw(true);
    }
  }else{

  }
}
</script>
@stop