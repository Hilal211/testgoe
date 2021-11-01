@extends('admin.layout.default')
@section('content')    
<div class="content-wrapper">
  <section class="content-header">
    <h1>Product Requests</h1>
  </section>
  <section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="box">
          <div class="box-body">
            <div class="table-responsive">
              <table id="ProductRequestTable" class="table no-margin table-actions table-small-padding">
                <thead>
                  <tr>
                    <th>Store</th>
                    <th>Product Name</th>
                    <th>Note</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Unit</th>
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
<div id="ProductModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    {!! Form::open(["url"=>route('store.product-request'),"method"=>"POST","class"=>"",'onsubmit'=>'return RequestProduct(this)']) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Request Product</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger form-errors display-none">
          <ul></ul>
        </div>
        <div class="form-group">
          @include('common.required_mark') {{ Form::label('product_name','Name',['class'=>'control-label']) }}
          {{ Form::text('product_name','',["class"=>'form-control','placeholder'=>'Product name',"spellcheck"=>"true"]) }}
        </div>
        <div class="row">
          <div class="form-group col-md-6">
            @include('common.required_mark') {{ Form::label('cat_id','Category',['class'=>'control-label']) }}
            {{ Form::select('category',$catsoptions,'',['class'=>'select2 form-control','data-placeholder'=>"select category",'onchange'=>'LoadSubCategories(this)']) }}
          </div>
          <div class="col-md-6 display-none">
            @include('common.required_mark') {{ Form::label('new_category','New Category',['class'=>'control-label']) }}
            {{ Form::text('new_category','',["class"=>'form-control']) }}
          </div>
        </div>
        <div class="row">
          <div class="form-group col-md-6">
            @include('common.required_mark') {{ Form::label('subcat_id','Sub Category',['class'=>'control-label']) }}
            {{ Form::select('sub_category',[],'', ['class'=>'select2 form-control','data-placeholder'=>"select category first",'onchange'=>'SubCategoryChnage(this)']) }}
          </div>
          <div class="form-group col-md-6 display-none">
            @include('common.required_mark') {{ Form::label('new_sub_category','New Sub category',['class'=>'control-label']) }}
            {{ Form::text('new_sub_category','',["class"=>'form-control']) }}
          </div>
        </div>
        <div class="form-group">
          @include('common.required_mark') {{ Form::label('unit','Unit',['class'=>'control-label']) }}
          {{ Form::select('unit',$units,'', ['class'=>'select2 form-control','data-placeholder'=>"select unit"]) }}
        </div>
        {{ Form::hidden('id','0',['id'=>'product_id']) }}
        {{ Form::hidden('store_id',Route::current()->getParameter('storeid')) }}
        <div class="form-group">
          @include('common.required_mark') {{ Form::label('image','Image',['class'=>'control-label']) }}
          {{ Form::file('image', ['data-multiple'=>false,'class' => 'file-input','id'=>'product-image']) }}
        </div>
        <div class="form-group">
          {{ Form::label('description','Note',['class'=>'control-label']) }}
          {{ Form::textarea('description','',["class"=>'form-control','placeholder'=>'Description / Note','rows'=>'3',"spellcheck"=>"true"]) }}
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
  var oTable = "";
  var Modal = $('#ProductModal');
  var Form = $(Modal).find('form');
  $(document).ready(function(){
    Form.find('select[name="cat_id"]').change();
    oTable = $('#ProductRequestTable').DataTable({
      processing: true,
      serverSide: true,
      ajax: 'requested-product-data',
      order: [['0','desc']],
      "drawCallback": function( settings ) {
        InitTooltip();
      }
    });
    $(Modal).on('hidden.bs.modal', function () {
      ResetForm(Form);
      $("#product-image").fileinput('clear');
      $(Form).find('input#product_id').val('0');
      $(Form).find('.form-errors').hide();

      $(Form).find('input, textarea').prop('disabled',false);
      $(Form).find('select').select2().enable(true);
      $(Form).find('.modal-footer').find('.btn').show();
    });
  })
  function ChangeStatus(element){
    var Id = $(element).data('id'),
    Status = $(element).data('status');
    $.ajax({
      type: "POST",
      url: APP_URL+'/admin/product-request-action',
      data: "id="+Id+"&status="+Status,
      dataType: "json",
      success: function(res) {
        oTable.draw(true);
      }
    });
  }
  function GetDelete(element) {
    var Id = $(element).attr('data-id');
    var IsDeleteMdal = $('#DeleteModal').length;
    if (IsDeleteMdal == '0') {
      MakeDeleteModal(Id, 'Delete Product', 'Are you sure you want to delete this product?', 'RemoveProduct()', '1');
    } else {
      MakeDeleteModal(Id, 'Delete Product', 'Are you sure you want to delete this product?', 'RemoveProduct()', '0');
    }
  }
  function RemoveProduct(){
    var Id = $('#delete-modal-id').val();
    $.ajax({
      type: "DELETE",
      url: APP_URL+'/store/request-product/' + Id,
      data: "",
      dataType: "json",
      success: function (res) {
        oTable.draw(true)
        $('#DeleteModal').loader('hide');
        $('#DeleteModal').modal('hide');
      }
    });
  }
  function viewProduct(element){
    var Id = $(element).data('id'),
    Status = $(element).data('status');
    $.ajax({
      type: "POST",
      url: APP_URL+'/product-request-details',
      data: {id:Id},
      dataType: "json",
      success: function(res) {
        $('#ProductModal').modal('show');
        SetFormValues(res.inputs,Form);
        $(Form).find('input, textarea').prop('disabled',true);
        $(Form).find('select').select2().enable(false);
        $(Form).find('.modal-footer').find('.btn').hide();
        $("#product-image").fileinput('destroy');
        if(res.inputs.image.file!=''){
          $("#product-image").fileinput('refresh',{
            'initialPreview': [
            '<img src="'+res.inputs.image.file+'" class="file-preview-image" width="auto">'
            ],
            'initialPreviewAsData': true,
            'overwriteInitial': true,
            'showUpload': false,
            'showRemove': false,
          });
          $('.kv-file-remove').prop('disabled',false).removeClass('disabled');
          $('.fileinput-remove').addClass('hide');
        }else{
          $("#product-image").fileinput('clear');
          $("#product-image").fileinput('refresh',{
            showUpload: false,
            showRemove: false,
          });
        }
      }
    });
  }
  function LoadSubCategories(element){
    console.log('aa');
    var cat = $(element).val();
    var Form = $(element).parents('form');
    var Holder = $(element).parents('.form-group');
    var NewCatHolder = Holder.next();
    var SubList = $('select[name="sub_category"]');
    NewCatHolder.addClass('display-none').find('input').val('');
    if(cat!=''){
      if(cat!='new'){
        $.ajax({
          type: "POST",
          url: APP_URL+"/get-subs",
          data: "cat_id="+cat,
          dataType: "json",
          beforeSend: function(msg){
            $(Form).loader('show');
          },
          success: function(res) {
            $(SubList).val('').change().html('');
            $(Form).loader('hide');
            if(res.sub_cats!=''){
              $.each(res.sub_cats, function(index, sub_cats){
                var HTML = "<option value='"+index+"'>"+sub_cats+"</option>";
                $(SubList).append(HTML);
              });
            }
            var HTML = "<option value='new'>Add New</option>";
            $(SubList).append(HTML);
            $(SubList).val('').change();
          }
        });
      }else{
        var HTML = "<option value='new'>Add New</option>";
        $(SubList).html(HTML);
        $(SubList).val('').change();
        NewCatHolder.removeClass('display-none');
      }
    }else{
      $(SubList).val('').change();
      $(SubList).html('');
    }
  }
  function SubCategoryChnage(element){
    var cat = $(element).val();
    var Form = $(element).parents('form');
    var Holder = $(element).parents('.form-group');
    var NewCatHolder = Holder.next();
    NewCatHolder.addClass('display-none').find('input').val('');
    if(cat=='new'){
      NewCatHolder.removeClass('display-none');
    }
  }
</script>
@stop