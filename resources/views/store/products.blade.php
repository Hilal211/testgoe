@extends('store.layout.default')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>Products</h1>
    <!-- <div class="form-group">
          {{ Form::label('Excel File',['class'=>'control-label']) }}
            {{ Form::file('bg_file', ['data-multiple'=>false,'class' => 'file-input','id'=>'background-file']) }}
            <button>upload</button>
        </div> -->
    <!-- <form method="GET" enctype="multipart/form-data" id="laravel-ajax-file-upload" action="">
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <input type="text" name="file" placeholder="Choose File" id="file">
            <span class="text-danger">{{ $errors->first('file') }}</span>
          </div>
        </div>
        <div class="col-md-12">
          <button type="submit" class="btn btn-primary">Submit</button>
        </div>
      </div>
    </form> -->
    <!-- {!! Form::open(["url"=>"/hilalo","method"=>"POST","class"=>"",'onsubmit'=>'return Hilalo()']) !!}
    {{ Form::text('name','',["class"=>'form-control','placeholder'=>'French Category name',"spellcheck"=>"true",'id'=>'inputt']) }}
    {{ Form::button(trans('keywords.Sign In'),["type"=>"submit","class"=>"btn btn-primary"]) }}
    {!! Form::close() !!} -->

     <form method="post" id="upload-file-form" enctype="multipart/form-data">
        <div class="form-group">
            <input type="file" name="file" class="form-control" id="file-input">
            <span class="text-danger" id="file-input-error"></span>
        </div>

        <div class="form-group">
          <button type="submit" class="btn btn-success">Upload</button>
        </div>

    </form>

  </section>
  <section class="content">
    <div class="row">
      @if(!$store_bank)
      <div class="col-md-12">
        <div class="callout callout-danger">
          <h4><i class="icon fa fa-ban"></i> Alert</h4>
          <p>Your Payment details is not ready yet, please fill bank details from profile in order to add product price.</p>
        </div>
      </div>
      @else
      @if($store_bank->status=='0')
      <div class="col-md-12">
        <div class="callout callout-danger">
          <h4><i class="icon fa fa-ban"></i> Alert</h4>
          <p>Your Payment details is not verified yet by Goecolo.</p>
        </div>
      </div>
      @endif
      @endif
      @if($no_charge_set)
      <div class="col-md-12">
        <div class="callout callout-danger">
          <h4><i class="icon fa fa-ban"></i> Alert</h4>
          <p>You must set your shipping charges from settings section.</p>
        </div>
      </div>
      @endif
      <div class="col-md-12">

      </div>
      <div class="col-md-6">
        <div class="panel-group categories-group" id="accordion">
          @foreach($cats as $cat)
          @include('common.dynamic.cats',['cats'=>$cats])
          @endforeach
        </div>
      </div>
      <div id="SubCatsHolder" class="col-md-6">

      </div>
    </div>
  </section>
</div>
<div id="ProductModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    {!! Form::open(["url"=>"store/save-product","method"=>"POST","class"=>"",'onsubmit'=>'return SaveStoreProduct(this)']) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Add Product</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger form-errors display-none">
          <ul></ul>
        </div>
        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('product_price','Price',['class'=>'control-label']) }}
              <div class="input-group">
                <span class="input-group-addon">$</span>
                {{ Form::text('price','',["class"=>'form-control','placeholder'=>'Product price   e.g 10.10']) }}
                {{-- <span class="input-group-addon">.00</span> --}}
                <span class="input-group-addon unit-addon">
                  {{ Form::label('unit','',['class'=>'control-label']) }}
                </span>
              </div>
            </div>
          </div>
          <div class="col-md-12">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('inventory','Inventory',['class'=>'control-label']) }}
              {{ Form::text('inventory','',["class"=>'form-control','placeholder'=>'Inventory']) }}
            </div>
          </div>
        </div>
        {{ Form::hidden('id','0',['id'=>'store_product_id']) }}
        {{ Form::hidden('store_id','0',['id'=>'store_id']) }}
        {{ Form::hidden('product_id','0',['id'=>'product_id']) }}
        <div class="row form-group">
          {{ Form::label('status','',['class'=>'control-label col-md-12']) }}
          <label class="col-md-3">
            {{ Form::checkbox('status','1','',["class"=>'i-check']) }} &nbsp; Active
          </label>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
      {!! Form::close() !!}
    </div>
  </div>
</div>
@stop

@section('custom_js')
<script>
  function GetDetais(element) {
    var JSON = $(element).data('json'),
      Id = JSON.id,
      parent = $(element).parents('.panel-heading');
    $.ajax({
      type: "POST",
      url: APP_URL + '/store/{{$store_id}}/get-subcats-view/' + Id,
      data: "",
      dataType: "json",
      success: function(res) {
        $('#accordion').find('.panel-heading').removeClass('bg-active');
        $(parent).addClass('bg-active');
        $('#SubCatsHolder').html(res.SubCategoryHtml);
      }
    });
  }

  function searchProduct(e, element, catId) {
    var regex = new RegExp("^[a-zA-Z0-9\b\s]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    var keywordVal = element.value;
    var length = keywordVal.length;

    if (regex.test(str)) {
      if (length == 0) {
        $.ajax({
          type: "POST",
          url: APP_URL + '/store/{{$store_id}}/get-subcats-view/' + catId,
          data: {},
          dataType: "json",
          success: function(res) {
            $('#SubCatsHolder').html(res.SubCategoryHtml);
          }
        });
      }
      if (length > 3) {
        $.ajax({
          type: "POST",
          url: APP_URL + '/store/{{$store_id}}/get-subcats-view/' + catId,
          data: {
            keyword: keywordVal
          },
          dataType: "json",
          success: function(res) {
            $('#SubCatsHolder').html(res.SubCategoryHtml);
            setTimeout(function() {
              var keywordVal = $('#search_input').val();
              $('#search_input').focus().val('').val(keywordVal);
            }, 500)
          }
        });
      }
    }
  }
  $(document).ready(function() {
    $('#ProductModal').on('hidden.bs.modal', function() {
      var Form = $(this).find('form')
      $(Form).find('input:checkbox').iCheck('uncheck');
      $(Form).find('input:checkbox').iCheck('update');
      $(Form).find('.form-errors').hide();
    })
  })

  function SetEdit(element) {
    var Form = $('#ProductModal').find('form')
    var Id = $(element).attr('data-id');
    var StoreId = $(element).attr('data-store');
    $.ajax({
      type: "POST",
      url: APP_URL + '/store/' + StoreId + '/product/' + Id,
      data: "",
      dataType: "json",
      success: function(res) {
        $('#ProductModal').modal('show');
        SetFormValues(res.inputs, Form);
      }
    })
  }

  function SaveStoreProduct(form) {
    var options = {
      target: '',
      url: $(form).attr('action'),
      type: 'POST',
      success: function(res) {
        /*var ProductId = $(form).find('input[name="product_id"]').val();
        var Product = $('#product_'+ProductId);
        $(Product).after(res.productHTML);
        $(Product).remove();*/
        $('.categories-group').find('.bg-active').find('a').click();
        $(form).parents('.modal').modal('hide');
      },
      error: function(jqXHR, exception) {
        var Response = jqXHR.responseText,
          ErrorBlock = $(form).find('.form-errors'),
          Response = $.parseJSON(Response);
        DisplayErrorMessages(Response, ErrorBlock, 'ul');
      }
    }
    $(form).ajaxSubmit(options);
    return false;
  }
</script>
<script type="text/javascript">
  // function Hilalo(){
  //   // var formData = new FormData(dd);
    
  //   var str = $("#inputt").val();
  //   data = new FormData();
  //   data.append("name", str);
  //   alert(str);
  //   $.ajax({
  //           type: "POST",
  //           url: APP_URL + '/store/{{$store_id}}/storehilalo',
  //           data: data,
  //           dataType: "json",
  //           success: function (response) {
  //           },
  //           error: function (jqXHR, exception) {
  //           }
  //       });
  //   return false;
  // }

   $('#upload-file-form').submit(function(e) {
       e.preventDefault();
       let formData = new FormData(this);
       $('#file-input-error').text('');

       $.ajax({
          type:'POST',
          url:APP_URL + '/store/{{$store_id}}/storehilalo',
           data: formData,
           contentType: false,
           processData: false,
           success: (response) => {
             if (response) {
               this.reset();
               alert('file has been uploaded successfully');
             }
           },
           error: function(response){
              console.log(response);
                $('#file-input-error').text(response.responseJSON.errors.file);
           }
       });
  });


</script>
@stop