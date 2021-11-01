@extends('admin.layout.default')

@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>Stores</h1>			
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="box">
					<div class="box-body">
						@include('common.message')  
						<div class="table-responsive">
							<table id="storetable" class="table table-bordered table-actions table-striped">
								<thead>
									<tr>
										<th>Store Name</th>
										<th>Contact Person</th>
										<th>Email</th>
										<th>Contact</th>
										<th>Home Delivery</th>
										<th>%</th>
										<th>Account Status</th>
										<th>Payment Status</th>
										<th>Category</th>
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
<div id="BankDetailsModal" class="modal fade" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Store Bank Details</h4>
      </div>
      {!! Form::open(["url"=>route('approve.bank'),"method"=>"POST",'onsubmit'=>'return ApproveBank(this)']) !!}
      <div class="modal-body">
      	<div class="row">
      		<div class="col-md-6">
      			<div class="form-group">
      				@include('common.required_mark') {!! Form::label('account_holder','Account Holder Name',['class'=>'control-label']) !!}
      				{!! Form::text('account_holder_name','',['class'=>'form-control','placeholder'=>'Enter Account Holder Name','readonly'=>'true']) !!}
      			</div>
      		</div>
      		<div class="col-md-6">
      			<div class="form-group">
      				@include('common.required_mark') {!! Form::label('bank_name','Bank Name',['class'=>'control-label']) !!}
      				{!! Form::text('bank_name','',['class'=>'form-control','readonly'=>'true','placeholder'=>'Enter Bank Name']) !!}
      			</div>
      		</div>
      	</div>
      	<div class="row">
      		<div class="col-md-6">
      			<div class="form-group">
      				@include('common.required_mark') {!! Form::label('account_number','Account Number',['class'=>'control-label']) !!}
      				{!! Form::text('account_number','',['class'=>'form-control','placeholder'=>'Enter Account Number','readonly'=>'true']) !!}
      			</div>
      		</div>
      		<div class="col-md-6">
      			<div class="form-group">
      				@include('common.required_mark') {!! Form::label('routing_number','Routing Number',['class'=>'control-label']) !!}
      				{!! Form::text('routing_number','',['class'=>'form-control','placeholder'=>'Enter Routing Number','readonly'=>'true']) !!}
      				{!! Form::hidden('id','') !!}
      			</div>
      		</div>
      	</div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Approve</button>
        </div>
      </div>
      {{ Form::close() }}
    </div>
  </div>
</div>
@stop

@section('custom_js')
<script>
	var oTable = ""
	$(document).ready(function(){
		oTable = $('#storetable').DataTable({
			processing: true,
			serverSide: true,
			ajax: 'store-data',
			order: [],
			"drawCallback": function( settings ) {
				InitTooltip(); 
			}
		});
	})
	function GetDelete(element) {
		var Id = $(element).attr('data-id');
		var IsDeleteMdal = $('#DeleteModal').length;
		if (IsDeleteMdal == '0') {
			$('body').loader('show');
			MakeDeleteModal(Id, 'Delete Store', 'Are you sure you want to delete this store?', 'RemoveStore()', '1');
		} else {
			MakeDeleteModal(Id, 'Delete Store', 'Are you sure you want to delete this store?', 'RemoveStore()', '0');
		}
	}
	function RemoveStore(){
		var Id = $('#delete-modal-id').val();
		$('#DeleteModal').loader('show');
		$.ajax({
			type: "DELETE",
			url: APP_URL+'/admin/store/'+Id,
			dataType: "json",
			success: function (res) {
				oTable.draw(true);
				$('#DeleteModal').loader('hide');
				$('#DeleteModal').modal('hide');
			}
		});
	}
	function ApproveStore(element){
		var Id = $(element).data('id');
		$.ajax({
			type: "POST",
			url: APP_URL+'/admin/approve-store/'+Id,
			dataType: "json",
			success: function (res) {
				oTable.draw(true);
			}
		});
	}
	function ApproveBank(form){
		var Data = $(form).serialize();
	    $.ajax({
	      type: "POST",
	      url: $(form).attr('action'),
	      data: Data,
	      dataType: "json",
	      success: function(res) {
	        $('#BankDetailsModal').modal('hide');
	        oTable.draw(true);
	      },
	      error : function(jqXHR, textStatus, errorThrown) {
	      	var StatusCode = jqXHR.status;
	      	if(StatusCode=='403'){
	      		toastr.error(jqXHR.responseJSON.error, 'Error!');
	      	}
	      },
	    });
	    return false;
	}
	function showBank(element){
		var Form = $('#BankDetailsModal').find('form')
		var Id = $(element).data('id');
		$.ajax({
			type: "POST",
			url: APP_URL+'/admin/store-bank/'+Id,
			dataType: "json",
			success: function (res) {
				SetFormValues(res.inputs,Form);
				$('#BankDetailsModal').modal('show');
			}
		});
	}
</script>
@stop