@extends('layouts.main')

@section('content')
<div class="title-bar">
	<h1 class="title-bar-title">
		<span class="d-ib"><i class="icon icon-users"></i> User Administration</span>
	</h1>
</div>
<div class="col-md-4">
	<div class="card">
		<div class="card-body">

			<div class="row">
				<div class="col-md-12">
					<div id="unitPriceDiv" class="form-group">
						<label>Full Name</label>
						<input id="fullName"  type="text" class="form-control" placeholder="Enter Name" />
						<input id="userId" style="display:none;" type="text" class="form-control" placeholder="Enter Name" />
					</div>
				</div>
				<div class="col-md-12">
					<div id="itemDiv" class="form-group">
						<label>Username</label>
						<input id="userName" type="text" class="form-control" placeholder="Enter Username"/>
					</div>
				</div>
				<div class="col-md-12">
					<div id="itemDiv" class="form-group">
						<label>Password</label>
						<input id="pWord" type="password" class="form-control" placeholder="Enter Password"/>
					</div>
					<div id="itemDiv" class="form-group">
						<label>Confirm Password</label>
						<input id="pWord2" type="password" class="form-control" placeholder="Confirm Password"/>
					</div>
				</div>
				<div class="col-md-6">
					<div id="statDiv" class="form-group">
						<label>User Type</label>
						<select id="uType" class="form-control" placeholder="Select UoM...">
							<option value="0">- Type -</option>
							<option value="1">ADMIN</option>
							<option value="3">CASHIER</option>
							<option value="2">SERVER</option>

						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div id="statDiv" class="form-group">
						<label>User Status</label>
						<select id="uStatus" class="form-control" placeholder="Select UoM...">
							<option value="0">- Status -</option>
							<option value="1">ACTIVE</option>
							<option value="2">INACTIVE</option>
						</select>
					</div>
				</div>	
			</div>

			<div class="row">
				<div class="col-md-12">
					<div id="itemDiv" class="form-group" style="float:right;">
						<button id="clearUser" class="btn btn-info">CLEAR</button>
						<button id="saveUser" class="btn btn-primary">SAVE</button>
						<button style="display: none;" id="upUser" class="btn btn-outline-primary">UPDATE</button>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<div class="col-md-8">
	<div class="card">
		<div class="card-body">
			<table id="userList" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th width='5%'>#</th>
						<th width='10%'>Name</th>
						<th width='10%'>Username</th>
						<th width='10%'>Type</th>
						<th width='10%'>Status</th>
						<th width='5%'></th>
					</tr>
				</thead>
				<tbody>

					<script>

						$('#userList').DataTable({
							ajax: "/showUserData",
							'bDestroy'    : true,
							'paging'      : true,
							'lengthChange': true,
							'searching'   : true,
							'ordering'    : true,
							'info'        : true,
							'autoWidth'   : true,
							'responsive'	: true,

							'columns': [
							{ 'data': 'id' },
							{ 'data': 'name' },
							{ 'data': 'email' },
							{
								"className": 'text-center',
								"data": null,
								"render": function (data, type, full, meta) {
									var type = data.type;
									if(type == 1)
									{
										return '<span class="label label-info" width="100px" style="width: 60px;">ADMIN</span>' ;
									}
									if( type == 3)
									{
										return '<span class="label label-danger" width="100px" style="width: 60px;">CASHIER</span>' ;
									}
									if( type == 2)
									{
										return '<span class="label label-danger" width="100px" style="width: 60px;">SERVER</span>' ;
									}


								}
							},
							{
								"className": 'text-center',
								"data": null,
								"render": function (data, type, full, meta) {
									var status = data.status;
									if(status == 1)
									{
										return '<span class="label label-success" width="100px" style="width: 60px;">ACTIVE</span>' ;
									}
									else
									{
										return '<span class="label label-danger" width="100px" style="width: 60px;">INACTIVE</span>' ;
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

</div>
@include('users.script.userScript')
@endsection