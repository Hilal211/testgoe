@extends('frontend.layout.default')

@section('content')
<div class="container">
	<div class="gap gap-small"></div>
	<div class="row bg-orange-rounded">
    <div class="registration-bg form-group">
      <div class="col-md-8 col-md-push-2">
       <h1 class="widget-title text-center">{{trans('keywords.My Orders')}}</h1>
       <p class="description text-center">{{trans('keywords.Unless already shipped, please note you have one hour from the time placed to cancel your order.')}}</p>
      </div>
    </div>
    <div>
      <div>
        <div class="col-md-12 table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="col-md-1">{{trans('keywords.Order #')}}</td>
                <td class="col-md-1">{{trans('keywords.Items')}}</td>
                <td class="col-md-1">{{trans('keywords.Order Date')}}</td>
                <td class="col-md-1">{{trans('keywords.Total')}}</td>
                <td class="col-md-1">{{trans('keywords.Status')}}</td>
                <td class="col-md-1">{{trans('keywords.Action')}}</td>
              </tr>
            </thead>
            <tbody>
             @foreach($myorders as $order)
             <tr>
              <td>
                <a href='javascript:;' data-id='{{$order->order_number}}' onclick='OpenInvoice(this)'>{{$order->order_number}}</a>
                <a href="javascript:;" class="show-tooltip copy_link" data-placement="top" data-id='{{$order->id}}' onclick='CopyProducts(this,"repeat")' title="Buy Now"><i class="fa fa-refresh"></i></a>
                <a href="javascript:;" class="show-tooltip copy_link" data-placement="top" data-id='{{$order->id}}' onclick='CopyProducts(this,"copy")' title="Add to Cart"><i class="fa fa-cart-plus"></i></a>
              </td>
              <td>{{$order->total_items}}</td>
              <td>{{Carbon::parse($order->created_at)->format('d M Y')}}</td>
              <td>{{Functions::GetPrice($order->grand_total)}}</td>
              <td class="{{$obj->Status[$order->status]['class']}}">{{trans('keywords.'.$obj->Status[$order->status]['title'])}}</td>
              @if($order->status=='7' || $order->status=='3' || $order->status=='9' || $order->status=='12')
                <td></td>
              @elseif($order->status=='8')
                @if($order->rating_id=='')
                  <td><a href="javascript:;" onclick="RateOrder(this)" data-id="{{$order->id}}" data-storename="{{$order->store_name}}" data-storeid="{{$order->store_id}}" class="login-link"><i class="fa fa-star"></i> {{trans('keywords.Rate It')}}</a></td>
                @else
                  <td>
                    <div class="col-md-6 no-padding">
                      <input class="rating-input" value="{{Functions::GetRate($order->rating)}}" type="number">
                    </div>
                    <div class="col-md-6">
                      <b>
                        <a href="javascript:;" class="show-tooltip" data-placement="top" title="Click to edit" onclick="OpenRatings(this)" data-rateid="{{$order->rating_id}}" data-id="{{$order->id}}" data-storename="{{$order->store_name}}" data-storeid="{{$order->store_id}}" >[{{Functions::GetRate($order->rating)}}]</a>
                      </b>    
                    </div>
                  </td>
                @endif
              @else
                <td><a href="javascript:;" onclick="CancelOrder(this)" data-id="{{$order->id}}">{{trans('keywords.Cancel')}}</a></td>
              @endif
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="text-center">
        {{ $myorders->render() }}    
      </div>
    </div>
  </div>
</div>
</div>
<div id="rateModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content text-center">
      {!! Form::open(["url"=>LaravelLocalization::getLocalizedURL(App::getLocale(),route('front.rate-order')),"method"=>"POST","class"=>"",'onsubmit'=>'return SaveRating(this)']) !!}
      <div class="modal-header">
        {{ Html::image(config('theme.ASSETS').config('theme.FRONT_IMG').'logo-center.png','',['width'=>'150px;']) }}
      </div>
      <div class="modal-body">
        <div class="form-group">
          <h3>{{trans('keywords.Rate Store')}}</h3>
          <h6>{{trans('keywords.How was your overall experience with')}} <span id="rate_store"></span> {{trans('keywords.store?')}}</h6>
        </div>
        <div class="alert alert-danger form-errors display-none text-left">
          <ul></ul>
        </div>
        <div class="form-group">
          <input id="input-21f" name="rating" class="form-control" value="0" type="number" min=0 max=5 step=0.5 data-size="md">
        </div>
        <div class="form-group">
          {!! Form::hidden('order_id','0') !!}
          {!! Form::hidden('store_id','0') !!}
          {!! Form::hidden('rate_id','0') !!}
          {{ Form::textarea('comments','',["class"=>'form-control','placeholder'=>trans('keywords.Comments'),'rows'=>'3']) }}
        </div>
      </div>
      <div class="modal-footer delete-actions">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{trans('keywords.Close')}}</button>
        <button type="submit" id="submit_btn" class="btn btn-primary">{{trans('keywords.Save')}}</button>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@stop
@section('page_custom_js')
<script>
  var RateLink = "";
  $(document).ready(function(){
    $("#input-21f").rating({
      theme: 'krajee-fa',
      filledStar: '<i class="fa fa-star"></i>',
      emptyStar: '<i class="fa fa-star-o"></i>',
      clearButton: '<i class="fa fa-ban"></i>',
      showClear: true,
      showCaption: false,
      hoverOnClear: false
    });
    $("#input-21f").rating().on("rating.change", function(event, value, caption) {
      //alert(value);
      //$("#input-21f").rating("refresh", {disabled:true, showClear:false});
    });
    InitRate();
    $('.clear-rating').hide();
    $('#rateModal').on('hidden.bs.modal', function () {
      var Form = $(this).find('form')
      $(Form).find('.clear-rating').click();
      $(Form).find('input[name="order_id"]').val('0');
      $(Form).find('input[name="rate"]').val('0');
      $(Form).find('input[name="rate_id"]').val('0');
      $(Form).find('textarea[name="comments"]').val('');
      $(Form).find('.form-errors').hide();
      $('#submit_btn').prop('disabled',false);
      $(Form).find('.form-errors').hide();
    })
  });
  function OpenInvoice(element){
    var Id = $(element).data('id');
    $.ajax({
      type: "POST",
      url: APP_URL+'/store/invoice/'+Id,
      data: {id:Id,'name':'orders'},
      dataType: "json",
      success: function(res) {
        $('#InvoiceModal').remove();
        $('body').append(res.InvoiceHTML);
        $('#InvoiceModal').modal('show');
        InitTooltip(); 
      }
    });
  }
  function CopyProducts(element,type){
    var Id = $(element).data('id');
    $.ajax({
      type: "POST",
      url: APP_URL+'/cart/copy/'+type,
      data: {id:Id},
      dataType: "json",
      success: function(res) {
        if(type=='copy'){
          toastr.success('{{trans("keywords.Cart Items Successfully updated")}}', Success, { onHidden: function() {
            reloadCartItem();
          }});
        }else{
          window.location.href=res.message;
        }
      }
    });
  }
  function CancelOrder(element){
    var Id = $(element).data('id');

    var IsDeleteMdal = $('#DeleteModal').length;

    Title = "{{trans('keywords.Cancel Order')}}";
    Msg = "{{trans('keywords.Are you sure you want to cancel order?')}}";
    
    if(IsDeleteMdal=='0'){
      $('body').loader('show');
      MakeDeleteModal(Id,Title,Msg,'ConfirmCancelOrder('+Id+')','1');
    }else{
      MakeDeleteModal(Id,Title,Msg,'ConfirmCancelOrder('+Id+')','0');
    }
  }
  function ConfirmCancelOrder(Id){
    $.ajax({
      type: "POST",
      url: APP_URL+'/customer/cancel-order/'+Id,
      data: "",
      dataType: "json",
      success: function(res) {

        toastr.success('{{trans("keywords.Order Cancelled Successfully, Money refunded to your card.")}}',Success, { onHidden: function() {
          $('#DeleteModal').modal('hide');
          window.location.reload();
        }});
      },
      error : function(jqXHR, textStatus, errorThrown) {
        var StatusCode = jqXHR.status;
        console.log("Error: aaaaa " + textStatus + ": " + errorThrown+" ");
        console.log(jqXHR);
        if(StatusCode=='403'){
          toastr.error(jqXHR.responseJSON.error,ErrorMsg);
        }
      },
    });
  }
  function RateOrder(element){
    var OrderId = $(element).attr('data-id');
    var StoreId = $(element).attr('data-storeid');
    var StoreName = $(element).attr('data-storename');
    var Modal = $('#rateModal');
    var Form = $('#rateModal').find('form');
    var OrderInput = $(Form).find('input[name="order_id"]');
    var StoreInput = $(Form).find('input[name="store_id"]');
    $(Form).find('#rate_store').html(StoreName);
    OrderInput.val(OrderId);
    StoreInput.val(StoreId);
    Modal.modal('show');
    RateLink = element;
  }
  function SaveRating(form){
    var Rate = $('input[name="rating"').val();
    var Data = $(form).serialize();
    $('#submit_btn').prop('disabled',true);
    $.ajax({
      type: "POST",
      url: $(form).attr('action'),
      data: Data,
      dataType: "json",
      success: function(res) {
        toastr.success('{{trans("keywords.Order Rated Successfully")}}',Success, { onHidden: function() {
          $('#rateModal').modal('hide');
          window.location.reload();
        }});
      },
      error: function (jqXHR, exception) {
        var Response = jqXHR.responseText,
        ErrorBlock = $(form).find('.form-errors'),
        Response = $.parseJSON(Response);
        DisplayErrorMessages(Response,ErrorBlock,'ul');
        $('#submit_btn').prop('disabled',false);
      }
    });
    return false;
  }
  function OpenRatings(element){
    var OrderId = $(element).attr('data-id');
    var StoreId = $(element).attr('data-storeid');
    var StoreName = $(element).attr('data-storename');
    var RateId = $(element).attr('data-rateid');
    var Modal = $('#rateModal');
    var Form = $('#rateModal').find('form');
    var OrderInput = $(Form).find('input[name="order_id"]');
    var StoreInput = $(Form).find('input[name="store_id"]');
    var RateInput = $(Form).find('input[name="rate_id"]');
    var CommentInput = $(Form).find('textarea[name="comments"]');
    $(Form).find('#rate_store').html(StoreName);
    OrderInput.val(OrderId);
    StoreInput.val(StoreId);
    RateInput.val(RateId);
    
    RateLink = element;
    $.ajax({
      type: "POST",
      url: APP_URL+'/customer/get_rate/'+RateId,
      data: "",
      dataType: "json",
      success: function(res) {
        $('#input-21f').rating('update',res.inputs.rating.value);
        SetFormValues(res.inputs,Form);
        Modal.modal('show');
      },
    });
  }


</script>

@stop