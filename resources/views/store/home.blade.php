@extends('store.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content">
    <div class="row">  
      <div class="col-md-4">
        <div class="box box-solid box-danger">
          <div class="box-header with-border">
            <i class="fa fa-thumbs-o-up"></i>
            <h3 class="box-title">New Orders</h3>
            <div class="box-tools pull-right">
              <span class="badge bg-aqua order_price"></span>
              <button class="btn btn-box-tool" onclick="RefreshOrder(this)"><i class="fa fa-refresh"></i></button>              
            </div>
          </div>
          <div class="box-body">
            <table id="Approved_OrderTable" data-type="1,2,4" class="table no-margin table-small-padding orderTable">
              <thead>
                <tr>
                  <th>Order#</th>
                  <th>Items</th>
                  <th>Ship To</th>
                  <th>Total</th>
                  <th>Actions</th>
                </tr>
              </thead>              
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="box box-solid box-warning">
          <div class="box-header with-border">
            <i class="fa fa-shopping-cart"></i>
            <h3 class="box-title">Processing Orders</h3>
            <div class="box-tools pull-right">
              <span class="badge bg-aqua order_price description"></span>
            </div>
          </div>
          <div class="box-body">
            <table id="Processing_OrderTable" data-type="5,6" class="table no-margin table-small-padding orderTable">
              <thead>
                <tr>
                  <th>Order#</th>
                  <th>Items</th>
                  <th>Ship To</th>
                  <th>Total</th>
                  <th>Actions</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="box box-solid box-success">
          <div class="box-header with-border">
            <i class="fa fa-truck"></i>
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
@section('left_sidebar')
{{-- <div class="col-md-6 hide">
  <div class="box box-primary">
    <div class="box-header with-border">
      <h3 class="box-title">Recently Added Products</h3>
      <div class="box-tools pull-right">

      </div>
    </div>
    <div class="box-body">
      <ul class="products-list product-list-in-box">
        @foreach($products as $product)
        <li class="item">
          <div class="product-img">
            {{ Html::image(Functions::UploadsPath(config('theme.PRODUCTS_UPLOAD')).Functions::GetImageName($product->image,'-32x32'),"",['class'=>'user-image']) }}
          </div>
          <div class="product-info">
            <a href="javascript::;" class="product-title">{{$product->category_name}}</a>
            <span class="product-description">{{$product->product_name}}</span>
          </div>
        </li>
        @endforeach
      </ul>
    </div>
  </div>
</div> --}}
@stop
@section('custom_js')
<script>
var Approved_OrderTable = "";
var Processing_OrderTable = "";
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
        "url": '{{route('store.order-data')}}',
        "type": "POST",
        "data": function (d) {
          d.key="value",
          d.status = data
        }
      },
      "drawCallback": function( settings ) {
          InitTooltip();
          SetResetTimer();
          GetTotals(Id);
      },
      "fnRowCallback": function(nRow,aData,iDisplayIndex,iDisplayIndexFull) {
          var JsonData = $(nRow).find('button.change-order-status').data('json');
          $(nRow).removeClass('blink_me');
          if(JsonData !== undefined){
            if(JsonData.blink !== undefined){
              $(nRow).addClass('blink_me');
            }
          }
      },
      "dom": 'rtp',
      'iDisplayLength': 10,
      "ordering": false
    });
  });

  // $('.table').on('click', '.change-order-status', function(e) {
  //   change_status(e);
  // });

  setInterval(blinker,1500);
  setInterval(function(){
    Approved_OrderTable.draw(true);
  },120000);
});
function GetTotals(tableId){  
  //$('.orderTable').each(function(i, row) {
    var Id = tableId,
        data = $('#'+tableId).data('type'),
        PriceHolder = $('#'+tableId).parents('.box').find('.order_price');
    $.ajax({
      type: "POST",
      url: '{{route('store.order-total')}}',
      data: "status="+data,
      dataType: "json",
      success: function(res) {
        $(PriceHolder).html(res.total);
      }
    });    
  //});
}
function SetResetTimer(){
  setTimeout(function(){
    $('#Shipped_OrderTable').find('tbody tr.blink_me').removeClass('blink_me').removeAttr('style');
    $('#Processing_OrderTable').find('tbody tr.blink_me').removeClass('blink_me').removeAttr('style');
  },3000)
}
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
function blinker() {
  $('.blink_me').each(function(i, row) {
    var Box = $(this).parents('.box')
    var Color = "";
    if(Box.hasClass('box-danger')){
      Color = '#dd4b39'
    }else if(Box.hasClass('box-warning')){
      Color = '#f39c12'
    }else if(Box.hasClass('box-success')){
      Color = '#00a65a'
    }
    $(this).fadeOut().css({
     'background-color' : Color,
     'color' : '#000'
   });
    $(this).fadeIn().css({
     'color' : '#fff'
   });
  })
  /*
  $('.blink_me').fadeOut().css({
     'background-color' : '#dd4b39',
     'color' : '#000'
  });
  $('.blink_me').fadeIn().css({
     'color' : '#fff'
  });*/
}

function RefreshOrder(element){
  Approved_OrderTable.draw(true);
}
function change_status(Element,event){
  var Row = $(Element).parents('tr');
  var JsonData = $(Element).data('json');
  var NewStatus = JsonData.new_status;
  var OldStatus = JsonData.old_status;
  var OrderId = JsonData.id;
  $.ajax({
    type: "POST",
    url: '{{route('store.order-status-change')}}',
    data: JsonData,
    dataType: "json",    
    success: function (res) {
      JsonData.row = Row;
      ChangeUI(res,JsonData,'tables');
    },
      error : function(jqXHR, textStatus, errorThrown) {
        var StatusCode = jqXHR.status;
        console.log("Error: aaaaa " + textStatus + ": " + errorThrown+" ");
        console.log(jqXHR);
        if(StatusCode=='403'){
          toastr.error(jqXHR.responseJSON.error,"");
          ChangeUI('',JsonData,'tables');
        }
      },
  });
}
function ChangeUI(response,data,type){
  if(type=='tables'){
    if(data.new_status=='4' || data.new_status=='3'){
      Approved_OrderTable.draw(true);
    }else if(data.new_status=='5'){
      Approved_OrderTable.draw(true);
      Processing_OrderTable.draw(true);      
    }else if(data.new_status=='7'){
      Processing_OrderTable.draw(true);
      Shipped_OrderTable.draw(true);
    }else if(data.new_status=='6'){
      Processing_OrderTable.draw(true);
    }else if(data.new_status=='8'){
      Shipped_OrderTable.draw(true);
    }
  }else{

  }
}
</script>
@stop