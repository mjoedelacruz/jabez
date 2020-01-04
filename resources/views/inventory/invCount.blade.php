@extends('layouts.main')

@section('content')
<div class="title-bar">
	<h1 class="title-bar-title">
		<span class="d-ib">INVENTORY COUNT</span>
	</h1>
</div>

<div class="card">
	<div class="card-header">
		<div class="col-md-4">
			<b>INVENTORY COUNTING - PRINT LIST</b>
		</div>
		<div class="col-md-8">
			<a href="/settleInvCount" id="invCount" value="0" data-action="1" style="float:right;" class="btn btn-sm btn-info">SETTLE INVENTORY</a>
		</div>
	</div>
	<div class="card-body">
		<table style="font-size:12px;" id="inventoryCountList" class="table table-bordered table-hover">
			<thead>
				<tr>
					<th width='5%'>Code</th>
					<th width='10%'>Name</th>
					<th width='10%'>Category</th>
					<th width='10%'>Unit</th>
					<!-- <th width='10%'>Qty</th> -->
					<!-- <th width='5%'>Status</th> -->
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
						'font-size': 12,
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

@endsection