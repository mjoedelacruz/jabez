@extends('layouts.main')

@section('content')
{{-- Start div --}}

{{-- Start div --}}
<div class="title-bar">
	<h1 class="title-bar-title">
		<div class="col-md-4">
			<span class="d-ib">INVENTORY MASTER LIST</span>
		</div>
		<div class="col-md-8">
			<a href="/settleInvCount" id="invCount" value="0" data-action="1" style="float:right;" class="btn btn-lg btn-primary"><i class="icon icon-dropbox"></i> START INVENTORY COUNT <i class="icon icon-dropbox"></i></a>
		</div>
	</h1>
</div>

<div class="card">
	<div class="card-header">
		<div class="col-md-2" >
			ITEM DATA 
		</div>
		<div class="col-md-2" >
		</div>
	</div>
	<div class="card-body">
		<div class="row">

			<div class="col-md-4">
				<div id="unitPriceDiv" class="form-group">
					<label>Item Code</label>
					<input id="itemCode" value="I-1" type="text" class="form-control" placeholder="Enter Item Code..." />
					<input id="itemCodeVal" style="display:none;"  value="{{$dateString}}" type="text" class="form-control" placeholder="Enter Item Code..." />
					<input id="itemId" value="" type="text" style="display:none;" class="form-control" placeholder="Enter Item Code..." />
				</div>
			</div>
			<div class="col-md-4">
				<div id="itemDiv" class="form-group">
					<label>Item Name</label>
					<input id="itemName" type="text" class="form-control" placeholder="Enter Item Name..."/>
				</div>
			</div>
				<!-- <div class="col-md-4">
					<div id="unitPriceDiv" class="form-group">
						<label>Selling Price</label>
						<input id="itemSellingPrice" type="text" class="form-control" placeholder="0.00"/>
					</div>
				</div> -->
				<div class="col-md-4">
					<div id="statDiv" class="form-group">
						<label>Item Status</label>
						<select id="itemStatus" class="form-control" placeholder="Select UoM...">
							<option value="0">- Select Status -</option>
							<option value="1">ACTIVE</option>
							<option value="2">INACTIVE</option>

						</select>
					</div>
				</div>	

			</div>

			<div class="row">
				<div class="col-md-4">
					<div class="col-md-6">
						<div id="itemDiv" class="form-group">
							<label>In Stock Qty</label>
							<input id="itemQty" type="text" class="form-control" disabled="" />
						</div>
					</div>
					<div class="col-md-6">
						<div id="itemDiv" class="form-group">
							<label>Shelf Life (Days)</label>
							<input value="0" id="itemStore" type="text" class="form-control" />
						</div>
					</div>
				</div>
				<div class="col-md-4">
					<div id="catDiv" class="form-group">
						<label>Category</label>
						<select id="itemCategory" class="form-control" placeholder="Select Category...">
							<option value="0">- Select Category -</option>
							@foreach($invcat as $ct)
							<option value="{{$ct->id}}">{{$ct->name}}</option>
							@endforeach
						</select>
						<button class="btn btn-link btn-sm" value="1" id="addCategory"><i class="icon icon-plus"></i> Add Category</button>
					</div>
					<div id="catDiv1" class="form-group" style="display:none;">
						<label>Category</label>
						<input id="itemCategoryText" type="text" class="form-control" placeholder="Enter Category Name..."/>
						<button class="btn btn-link btn-sm" value="1" id="cancelCategory"><i class="icon icon-close"></i> Cancel</button>
					</div>
				</div>
				<div class="col-md-4">
					<div id="uomDiv" class="form-group">
						<label>UoM</label>
						<select id="itemUom" class="form-control" placeholder="Select UoM...">
							<option value="0">- Select Uom -</option>
							@foreach($uoms as $u)
							<option value="{{$u->id}}">{{$u->name}}</option>
							@endforeach
						</select>
						<button class="btn btn-link btn-sm" value="1" id="addUom"><i class="icon icon-plus"></i> Add Uom</button>
					</div>
					<div id="itemUomDiv" class="form-group" style="display:none;">
						<label>UoM</label>
						<input id="itemUomText" type="text" class="form-control" placeholder="Enter Category Name..."/>
						<button class="btn btn-link btn-sm" value="1" id="cancelUom"><i class="icon icon-close"></i> Cancel</button>
					</div>
				</div>
				


			</div>

			<div class="row">
				<div class="col-md-3">
				</div>
				<div class="col-md-3">
				</div>
				<div class="col-md-3">
				</div>
				<div class="col-md-3">
					<div class="form-group">
						<button id="addItem" value="0" data-action="1" style="float:right;" class="btn btn-primary">Add Item</button>
						<button id="updateItem" class="btn btn-outline-success" type="button" style="float:right;display:none;">Update Item
						</button> 
						<button id="newItem" value="0" data-action="2" style="float:right;display:none;" class="btn btn-lg btn-info">New Item</button>
						<button data-action="1" id="activeAddItem" class="btn btn-info" style="float:right;">
							NEW
						</button> 
						
						


					</div>
				</div>
			</div>
		</div>

	</div>

	<div class="card">
		<div class="card-header">
			INVENTORY LIST
		</div>
		<div class="card-body">
			<table id="inventoryList" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width='5%'>Code</th>
						<th width='10%'>Name</th>
						<th width='10%'>Category</th>
						<th width='10%'>Unit</th>
						<th width='10%'>Qty</th>
						<th width='5%'>Shelf Life</th>
						<th width='5%'>Status</th>
						<th width='5%'>Action</th>
					</tr>
				</thead>
				<tbody>

					<script>

						$('#inventoryList').DataTable({
							ajax: "{{route('invData')}}",
							'bDestroy'    : true,
							'paging'      : true,
							'lengthChange': true,
							'searching'   : true,
							'ordering'    : true,
							'info'        : true,
							'autoWidth'   : true,
							'responsive'	: true,
							
							'columns': [
							{ 'data': 'code' },
							{ 'data': 'itemName' },
							{ 'data': 'invcatname' },
							{ 'data': 'uom' },
							{ 'data': 'qty' },
							{ 
								"className": 'text-center',
								"data": null,
								"render": function (data, type, full, meta) {
									return data.sd+' Days';

								}		
							},
							{
								"className": 'text-center',
								"data": null,
								"render": function (data, type, full, meta) {
									var stat = data.status;
									if(stat == 1)
									{
										return '<span class="label label-success" width="100px" style="width: 60px;">Active</span>' ;
									}
									else
									{
										return '<span class="label label-danger" width="100px" style="width: 60px;">Inactive</span>' ;
									}

								}
							},
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
	{{-- end div --}}

	{{-- end div --}}


	@include('inventory.script.invScript');

	@endsection