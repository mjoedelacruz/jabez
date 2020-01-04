@extends('layouts.main')

@section('content')
<div class="title-bar">
	<h1 class="title-bar-title">
		<span class="d-ib">Business Partners</span>
	</h1>
</div>

<div class="card">
	<div class="card-header">
		<div class="col-md-3" >
			BUSINESS PARTNER DATA 
		</div>
		
	</div>
	<div class="card-body">
		<div class="row">

			<div class="col-md-4">
				<div id="unitPriceDiv" class="form-group">
					<label>BP Code</label>
					<input id="bpCode" value="BP-1" type="text" class="form-control" placeholder="Enter BP Code..." />
					
					<input id="bpId" value="" type="text" style="display:none;" class="form-control" placeholder="Enter Item Code..." />
				</div>
			</div>
			<div class="col-md-4">
				<div id="itemDiv" class="form-group">
					<label>BP Name</label>
					<input id="bpName" type="text" class="form-control" placeholder="Enter BP Name"/>
				</div>
			</div>
			<div class="col-md-4">
				<div id="unitPriceDiv" class="form-group">
					<label>Contact Person</label>
					<input id="bpPerson" type="text" class="form-control" placeholder="Contact Person"/>
				</div>
			</div>
		</div>

		<div class="row">

			<div class="col-md-4">
				<div id="unitPriceDiv" class="form-group">
					<label>Contact #</label>
					<input id="bpContact" value="" type="text" class="form-control" placeholder="Contact #" />
					
					
				</div>
			</div>
			<div class="col-md-4">
				<div id="itemDiv" class="form-group">
					<label>Email</label>
					<input id="bpEmail" type="text" class="form-control" placeholder="Enter Email..."/>
				</div>
			</div>
			<div class="col-md-4">
				<div id="unitPriceDiv" class="form-group">
					<label>Address</label>
					<input id="bpAddress" type="text" class="form-control" placeholder="Enter Address"/>
				</div>
			</div>


		</div>

		<div class="row">
			<div class="col-md-4">
				<div id="bpStatDiv" class="form-group">
					<label>Status</label>
					<select id="bpStatus" class="form-control" placeholder="Select UoM...">
						<option value="">- Select Status -</option>
						<option value="1">ACTIVE</option>
						<option value="2">INACTIVE</option>

					</select>
				</div>
			</div>
			<div class="col-md-4">
				<div id="bpTypeDiv" class="form-group">
					<label>Status</label>
					<select id="bpType" class="form-control" placeholder="Select UoM...">
						<option value="">- Select Type -</option>
						<option value="1">CUSTOMER</option>
						<option value="2">SUPPLIER</option>

					</select>
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
					<button data-action="1"  id="clearBp" class="btn btn-info">
						New
					</button> 
					<button id="addBp" value="0" data-action="1" style="float:right;" class="btn btn-primary">Add</button>
					<button id="newBp" value="0" data-action="2" style="float:right;display:none;" class="btn btn-lg btn-info">New</button>
					<button id="updateBp" class="btn btn-outline-success" type="button" style="float:right;display:none;">Update
					</button> 

				</div>
			</div>
		</div>
	</div>
</div>

<div class="card">
	<div class="card-header">
		BP LIST
	</div>
	<div class="card-body">
		<table id="bpList" class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width="5%">Code</th>
					<th width="10%">Name</th>
					<th width="10%">Type</th>
					<th width="5%">C. Person</th>
					<th>Contact #</th>
					<!-- <th>Address</th> -->
					<th>Email</th>
					<th>Status</th>
					<th></th>
					
				</tr>
			</thead>
			<tbody>

				<script>

					$('#bpList').DataTable({
						ajax: "{{route('bpData')}}",
						'bDestroy'    : true,
						'paging'      : true,
						'lengthChange': true,
						'searching'   : true,
						'ordering'    : true,
						'info'        : true,
						'autoWidth'   : true,
						'responsive'	: true,

						'columns': [
						{ 'data': 'bpCode' },
						{ 'data': 'name' },
						{
							"className": 'text-center',
							"data": null,
							"render": function (data, type, full, meta) {
								var type = data.type;
								if(type == 1)
								{
									return '<span class="label label-info" width="100px" style="width: 60px;">Customer</span>' ;
								}
								else
								{
									return '<span class="label label-outline-info" width="100px" style="width: 60px;">Supplier</span>' ;
								}

							}
						},
						{ 'data': 'contactPerson' },
						{ 'data': 'contactNo' },
              //{ 'data': 'address' },
              { 'data': 'email'},
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

@include('businesspartners.script.bpScript')
@endsection