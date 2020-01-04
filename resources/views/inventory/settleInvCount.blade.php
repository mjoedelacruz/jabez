@extends('layouts.main')

@section('modal')
<div id="successModalAlert" tabindex="-1" role="dialog" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="card">
				<div class="card-body">
					<table id="inventoryCountList" class="table table-bordered table-hover" style="width: 100%;">
						<thead>
							<tr>
								<th width='5%'>Code</th>
								<th width='15%'>Name</th>
								<th width='15%'>Category</th>
								<th width='10%'>Unit</th>
								<th width='10%'>Qty</th>
							</tr>
						</thead>
						<tbody >
							<script>

								$('#inventoryCountList').DataTable({
									ajax: "{{route('invData')}}",
									'bDestroy'    : true,
									'paging'      : true,
									'lengthChange': true,
									'searching'   : true,
									'ordering'    : true,
									'info'        : true,
									'autoWidth'   : true,
									'responsive'	: true,
									'dom': 'Bfrtip',
									'buttons': [
									'print',
									],
									'columns': [
									{ 'data': 'code' },
									{ 'data': 'itemName' },
									{ 'data': 'invcatname' },
									{ 'data': 'uom' },
									{ 
										"className": 'options',
										"data":    null,
										"render": function(data, type, full, meta){
											var valueHere=data.id;

											return '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
										}
									},
									]
								});
							</script>

						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

@stop

@section('content')
<div class="title-bar">
	<h1 class="title-bar-title">
		<span class="d-ib">INVENTORY COUNT</span>
	</h1>
</div>

<div class="card">
	<div class="card-header">
		<div class="col-md-6">
			<b>SETTLE INVENTORY</b>
		</div>
		<div class="col-md-6">
			<button style="float:right;" class="btn btn-outline-success" data-toggle="modal" data-target="#successModalAlert" type="button">PRINT INVENTORY DATA</button>
		</div>
	</div>
	<div class="card-body">

		
		<div class="col-md-3">
			
			<!-- <label>Choose Inventory Item</label> -->
			<div class="form-group">
				<select id="settleInvItem" class="form-control" >
					<option value="">- Select Item -</option>
					@foreach($inv as $i)
					<option value="{{$i->code}}">{{$i->name}}</option>
					@endforeach
				</select>
			</div>
			<!-- <p><i style="color:green;">Select Item</i></p> -->
			<div class="form-group">
				<textarea id="settleRemarks" class="form-control" placeholder="Remarks here..."></textarea>
			</div>
		</div>


		<div class="col-md-9">
			<table id="settleInvList" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width='5%'>Code</th>
						<th width='15%'>Name</th>
						<th width='10%'>Category</th>
						<th width='5%'>Unit</th>
						<th width='10%'>Current Qty</th>
						<th width='10%'>New Qty</th>
						<th  width='5%'></th>
					</tr>
				</thead>
				<tbody id="settleRowList">
					<script>
						$(document).ready(function() {
							$('#settleInvList').DataTable( {
								"scrollY":        "300px",
								"scrollCollapse": true,
								"paging":         false,
								"searching": false,
								"oLanguage": {"sZeroRecords": "", "sEmptyTable": ""},
								"bInfo": false,
								"orderable": false,
								'order': [],
								columnDefs: [ { orderable: false, targets: [0,1,2,3,4,5,6] } ],
							} );
						} );
					</script>
				</tbody>
			</table>
		</div>
		
	</div>
	<div class="card-footer">
		<div class="col-md-12">
			<button id="setInvQty" value="0" data-action="1" style="float:right;" class="btn btn-sm btn-primary">SETTLE INVENTORY</button>
			<button id="clearTable" value="0" data-action="1" style="float:right;" class="btn btn-sm btn-info">CLEAR TABLE</button>
			
		</div>
	</div>
</div>

<div class="col-md-6">
	<div class="card">
		<div class="card-header">
			<b>INVENTORY COUNT LIST</b>


		</div>
		<div class="card-body">
			<table id="invCountList" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width='5%'>#</th>
						<th width='15%'>User</th>
						<th width='15%'>Remarks</th>
						<th width='10%'>Date</th>
						<th  width='5%'></th>
					</tr>
				</thead>
				<tbody id="">
					<script>
						$('#invCountList').DataTable({
							ajax: "{{route('invCountList')}}",
							'bDestroy'    : true,
							'paging'      : true,
							'lengthChange': true,
							'searching'   : true,
							'ordering'    : true,
							'info'        : true,
							'autoWidth'   : true,
							'responsive'	: true,

							'columns': [
							{ 'data': 'icId' },
							{ 'data': 'uName' },
							{ 'data': 'rem' },
							{ 'data': 'Date' },
							{ 
								"className": 'options',
								"data":    null,
								"render": function(data, type, full, meta){
									var valueHere=data.id;

									return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
								}

							},
							]
						});	
					</script>
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="col-md-6">
	<div class="card">
		<div class="card-header">
			<b>INVENTORY COUNT DETAILS</b>
		</div>
		<div class="card-body">
			<table id="invCountDetailsList" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width='5%'>#</th>
						<th width='5%'>Code</th>
						<th width='10%'>Name</th>
						<th  width='5%'>Prev. Qty</th>
						<th  width='5%'>Upd. Qty</th>
						<th  width='5%'>Discrepancy</th>
					</tr>
				</thead>
				<tbody>
					<!-- <script>
						$('#invCountDetailsList').DataTable( {
							'bDestroy'    : true,
							'paging'      : true,
							'lengthChange': true,
							'searching'   : true,
							'ordering'    : true,
							'info'        : true,
							'autoWidth'   : true,
							'responsive'	: true,
								"scrollY":        "300px",
								"scrollCollapse": true,
							} );
						</script> -->
					</tbody>
				</table>
			</div>
		</div>
	</div>
	@include('inventory.script.settleInvScript')
	@endsection