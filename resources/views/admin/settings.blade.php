@extends('admin.layout.default')

@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>Settings</h1>
	</section>
	<section class="content">
		<div class="row">

			@foreach($commitionareas as $area)
			<div class="col-md-6">
				@include('admin.single-items.settings_area',['area'=>$area])
			</div>
			@endforeach

			{{-- @foreach($shippingareas as $area)
			<div class="col-md-4">
				@include('admin.single-items.settings_area',['area'=>$area])
			</div>
			@endforeach --}}
			<div class="col-md-6">
				<div class="box box-info collapsed-box">
					<div class="box-header">
						<h3 class="box-title">Shipping Cost (A * X + B)</h3>
						<div class="box-tools pull-right">
             <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
             </button>
           </div>
         </div>
         <div class="box-body">
          @include('errors.error')
          {!! Form::open(["url"=>route("save.shipping"),"method"=>"POST"]) !!}
          @if($defaultCharge->icon=='')

          <div class="form-group">
           @include('common.required_mark') {!! Form::label($defaultCharge->slug,$defaultCharge->area,['class'=>'control-label']) !!}
           {!! Form::text($defaultCharge->slug,$defaultCharge->value,['class'=>'form-control','placeholder'=>'Enter '.$defaultCharge->area.'']) !!}
         </div>
         @else
         @include('common.required_mark') {!! Form::label($defaultCharge->slug,'A = '.$defaultCharge->area.' ($)',['class'=>'control-label']) !!}
         <div class="form-group input-group">
           <span class="input-group-addon"><i class="{{$defaultCharge->icon}}"></i></span>
           {!! Form::text($defaultCharge->slug,$defaultCharge->value,['class'=>'form-control','placeholder'=>'Enter '.$defaultCharge->area.'']) !!}
         </div>

         <p><b>X = Miles between Customer and Store locations</b></p>

         @include('common.required_mark') {!! Form::label($minimumShipping->slug,'B = '.$minimumShipping->area.' ($)',['class'=>'control-label']) !!}
         <div class="form-group input-group">
           <span class="input-group-addon"><i class="{{$minimumShipping->icon}}"></i></span>
           {!! Form::text($minimumShipping->slug,$minimumShipping->value,['class'=>'form-control','placeholder'=>'Enter '.$minimumShipping->area.'']) !!}
         </div>
         @endif
         {{ Form::button('Save',["type"=>"submit","class"=>"btn btn-primary pull-right"]) }}
         {!! Form::close(); !!}
       </div>
     </div>
   </div>
 </div>
 <div class="row">
   <div class="col-md-6">
    <div class="box box-info collapsed-box">
     <div class="box-header">
      <h3 class="box-title">Store Types</h3>
      <div class="box-tools pull-right">
       <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
       </button>
     </div>
   </div>
   <div class="box-body">
    <table class="table no-margin table-small-padding storetypetable">
     <tbody>
      <tr id="newStoretypeRow" class="text-center">
       <td colspan="2"><a href="#StoreTypeModal" data-toggle="modal"><i class="fa fa-plus"></i> Add New</a></td>
     </tr>
     @foreach($storetypes as $store)
     <tr id="store_type_{{$store->id}}">
      <td>{{$store->item_name}}</td>
      <td>
       <a href="javascript:;" class="btn btn-xs" onclick="SetEditList(this)" data-type="store_type" data-id="{{$store->id}}"><i class="fa fa-pencil"></i></a>
       <a href="javascript:;"  class="btn btn-xs" onclick="SetDeleteList(this)" data-type="store_type" data-id="{{$store->id}}"><i class="fa fa-remove"></i></a>
     </td>
   </tr>
   @endforeach
 </tbody>
</table>
</div>
</div>
</div>
<div class="col-md-6">
  <div class="box box-info collapsed-box">
   <div class="box-header">
    <h3 class="box-title">Units</h3>
    <div class="box-tools pull-right">
     <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-plus"></i>
     </button>
   </div>
 </div>
 <div class="box-body">
  <table class="table no-margin table-small-padding unittable">
   <tbody>
    <tr id="newUnitRow" class="text-center">
     <td colspan="2"><a href="#UnitModal" data-toggle="modal"><i class="fa fa-plus"></i> Add New</a></td>
   </tr>
   @foreach($units as $unit)
   <tr id="unit_{{$unit->id}}">
    <td>{{$unit->friendly_name}}</td>
    <td>
     <a href="javascript:;" class="btn btn-xs" onclick="SetEditList(this)" data-type="unit" data-id="{{$unit->id}}"><i class="fa fa-pencil"></i></a>
     <a href="javascript:;"  class="btn btn-xs" onclick="SetDeleteList(this)" data-type="unit" data-id="{{$unit->id}}"><i class="fa fa-remove"></i></a>
   </td>
 </tr>
 @endforeach
</tbody>
</table>
</div>
</div>
</div>
</div>
</section>
</div>
<div id="StoreTypeModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    {!! Form::open(["url"=>"/admin/save/storetype","method"=>"POST","class"=>"",'onsubmit'=>'return SaveStoreType(this)']) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Store Types</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger form-errors display-none">
          <ul></ul>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('item_name','Store Type',['class'=>'control-label']) }}
              {{ Form::text('item_name','',["class"=>'form-control','placeholder'=>'Store Type',"spellcheck"=>"true"]) }}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('value_1','French Store Type',['class'=>'control-label']) }}
              {{ Form::text('value_1','',["class"=>'form-control','placeholder'=>'French Store Type',"spellcheck"=>"true"]) }}
            </div>
          </div>
        </div>
        {{ Form::hidden('id','0',['id'=>'type_id']) }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>
<div id="UnitModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    {!! Form::open(["url"=>"/admin/save/unit","method"=>"POST","class"=>"",'onsubmit'=>'return SaveUnit(this)']) !!}
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Units</h4>
      </div>
      <div class="modal-body">
        <div class="alert alert-danger form-errors display-none">
          <ul></ul>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('unit','Unit Name',['class'=>'control-label']) }}
              {{ Form::text('friendly_name','',["class"=>'form-control','placeholder'=>'Unit',"spellcheck"=>"true"]) }}
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              @include('common.required_mark') {{ Form::label('unit','Short Unit Name',['class'=>'control-label']) }}
              {{ Form::text('item_name','',["class"=>'form-control','placeholder'=>'Unit',"spellcheck"=>"true"]) }}
            </div>
          </div>
        </div>
        {{ Form::hidden('id','0',['id'=>'type_id']) }}
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
    {!! Form::close() !!}
  </div>
</div>
@stop
@section('custom_js')
<script>
  $(document).ready(function(){
     $('#StoreTypeModal').on('hidden.bs.modal', function () {
      var Form = $(this).find('form')
      $(Form).find('input[name="id"]').val('0');
      $(Form).find('input[name="item_name"]').val('');
      $(Form).find('input[name="value_1"]').val('');
      $(Form).find('.form-errors').hide();
    })
     $('#UnitModal').on('hidden.bs.modal', function () {
      var Form = $(this).find('form')
      $(Form).find('input[name="id"]').val('0');
      $(Form).find('input[name="friendly_name"]').val('');
      $(Form).find('input[name="item_name"]').val('');
      $(Form).find('.form-errors').hide();
    })
 });
  function SetEditList(element){
    var Id = $(element).attr('data-id');
    var Type = $(element).attr('data-type');

    if(Type=='store_type'){
      var Form = $('#StoreTypeModal').find('form');  
    }else{
      var Form = $('#UnitModal').find('form');  
    }

    $(Form).find('input[name="id"]').val(Id);
    $.ajax({
      type: "POST",
      url: APP_URL+'/admin/_list/'+Id,
      data: {'type':Type},
      dataType: "json",
      success: function(res) {
        if(Type=='store_type'){
          $('#StoreTypeModal').modal('show');
        }else{
          $('#UnitModal').modal('show');
        }
        SetFormValues(res.inputs,Form);
      }
    });
  }
  function SaveStoreType(form){
   var options = {
      target: '',
      url: $(form).attr('action'),
      type: 'POST',
      success: function(res) {
         var Id = $(form).find('input[name="id"]').val();
         var Title = $(form).find('input[name="item_name"]').val();
         if(res.message=='old'){
           $('#store_type_'+Id).find('td:eq(0)').html(Title);
           $('#StoreTypeModal').modal('hide');
         }else{
          $('#newStoretypeRow').next().clone().appendTo('.storetypetable tbody');
          $('.storetypetable tbody tr:last-child').attr('id','store_type_'+res.item.id);
          $('.storetypetable tbody tr:last-child').find('td:eq(0)').html(Title);
          $('.storetypetable tbody tr:last-child').find('a').attr('data-id',res.item.id);
          $('#StoreTypeModal').modal('hide');
        }
      },
      error: function (jqXHR, exception) {
        var Response = jqXHR.responseText,
        ErrorBlock = $(form).find('.form-errors'),
        Response = $.parseJSON(Response);
        DisplayErrorMessages(Response,ErrorBlock,'ul');
      }
   }
   $(form).ajaxSubmit(options);
   return false;
}
function SetDeleteList(element){
  var Id = $(element).attr('data-id');
  var Type = $(element).attr('data-type');
  var IsDeleteMdal = $('#DeleteModal').length;
  if(Type=='store_type'){
    var Title = "Delete Store Type";
    var Msg = "Are you sure you want to delete this type?";
    if(IsDeleteMdal=='0'){
      $('body').loader('show');
      MakeDeleteModal(Id,Title,Msg,'RemoveList("storetype")','1');
    }else{
      MakeDeleteModal(Id,Title,Msg,'RemoveList("storetype")','0');
    }
  }else{
    var Title = "Delete Unit";
    var Msg = "Are you sure you want to delete this unit?";
    if(IsDeleteMdal=='0'){
      $('body').loader('show');
      MakeDeleteModal(Id,Title,Msg,'RemoveList("unit")','1');
    }else{
      MakeDeleteModal(Id,Title,Msg,'RemoveList("unit")','0');
    }
  }
}
function RemoveList(type){
  var Id = $('#delete-modal-id').val();
  $('#DeleteModal').loader('show');
  $.ajax({
    type: "DELETE",
    url: APP_URL+'/admin/list/'+Id,
    data: "",
    dataType: "json",
    success: function(res) {
      if(type=='storetype'){
        $('#store_type_'+Id).remove(); 
      }else{
        $('#unit_'+Id).remove(); 
      }
      $('#DeleteModal').loader('hide');
      $('#DeleteModal').modal('hide');
    }
  });
}
function SaveUnit(form){
  var options = {
    target: '',
    url: $(form).attr('action'),
    type: 'POST',
    success: function(res) {
      var Id = $(form).find('input[name="id"]').val();
      var Title = $(form).find('input[name="friendly_name"]').val();
      if(res.message=='old'){
        $('#unit_'+Id).find('td:eq(0)').html(Title);
        $('#UnitModal').modal('hide');
      }else{
        $('#newUnitRow').next().clone().appendTo('.unittable tbody');
        $('.unittable tbody tr:last-child').attr('id','unit_'+res.item.id);
        $('.unittable tbody tr:last-child').find('td:eq(0)').html(Title);
        $('.unittable tbody tr:last-child').find('a').attr('data-id',res.item.id);
        $('#UnitModal').modal('hide');
      }
    },
    error: function (jqXHR, exception) {
      var Response = jqXHR.responseText,
      ErrorBlock = $(form).find('.form-errors'),
      Response = $.parseJSON(Response);
      DisplayErrorMessages(Response,ErrorBlock,'ul');
    }
  }
  $(form).ajaxSubmit(options);
  return false;
}
</script>
@stop