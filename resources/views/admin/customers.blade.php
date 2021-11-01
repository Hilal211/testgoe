@extends('admin.layout.default')

@section('content')
<div class="content-wrapper">
	<section class="content-header">
		<h1>Shoppers</h1>
	</section>
	<section class="content">
		<div class="row">
			<div class="col-md-12 col-lg-12">
				<div class="box">
					<div class="box-body">
						@include('common.message')
						<div class="table-responsive">
							<table id="CustomerTable" class="table table-bordered table-actions table-striped">
								<thead>
									<tr>
										<th>Shopper Name</th>
										<th>Email</th>
										<th>Province</th>
										<th>City</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
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
		oTable = $('#CustomerTable').DataTable({
			processing: true,
			serverSide: true,
			ajax: 'customer-data',
			"drawCallback": function( settings ) {
				$('[data-toggle="tooltip"]').tooltip(); 
			},
			columns: [
				{data: '0', name: 'users.firstname'},
				{data: '1', name: 'users.email'},
				{data: '2', name: 'state.item_name'},
				{data: '3', name: 'city.item_name'},
			]
		});
	})
	function GetDelete(element) {
		var Id = $(element).attr('data-id');
		var IsDeleteMdal = $('#DeleteModal').length;
		if (IsDeleteMdal == '0') {
			$('body').loader('show');
			MakeDeleteModal(Id, 'Delete Shopper', 'Are you sure you want to delete this shopper?', 'RemoveShopper()', '1');
		} else {
			MakeDeleteModal(Id, 'Delete Shopper', 'Are you sure you want to delete this shopper?', 'RemoveShopper()', '0');
		}
	}
	function RemoveShopper(){
		var Id = $('#delete-modal-id').val();
		$('#DeleteModal').loader('show');
		$.ajax({
			type: "DELETE",
			url: APP_URL+'/admin/shopper/'+Id,
			dataType: "json",
			success: function (res) {
				oTable.draw(true);
				$('#DeleteModal').loader('hide');
				$('#DeleteModal').modal('hide');
			}
		});
	}
</script>
@stop