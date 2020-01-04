<div class="tab-pane fade" id="home-11">
	
	<div class="card" >
		<div class="card-body">
			<div class="row">
				<div class="col-md-2 form-group">
					<input placeholder="Date Start" name="dateStarts"  id="dateStart" class="form-control-sm form-control" type="text"><p><i style="color: #029acf;">Date Start</i></p>
				</div>

				<div class="col-md-2 form-group">
					<input value="" placeholder="Date End" name="dateEnds" id="dateEnd" class="form-control-sm form-control" type="text"><p><i style="color: orange;">Date End</i></p>
				</div>
				<div class="col-md-2 form-group" >
					<button id="sortDate" class="btn btn-success">Sort Report</button>
					<!-- <p><i><a id="exportInvData" style="color: #029acf;"><span class="icon icon-download"></span> Download Report</a></i></p> -->
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-body">
			<table id="inventoryListReport" class="table table-bordered table-hover" style="width:100%;">
				<thead>
					<tr>
						<th width='5%'>Code</th>
						<th width='10%'>Name</th>
						<th width='10%'>Category</th>
						<th width='10%'>Unit</th>
						<th width='5%'>Qty</th>
						<th width='5%'>Status</th>
						<th width='10%'>Date</th>
						<th width='5%'></th>
					</tr>
				</thead>
				<tbody>

					<script>
						var d = new Date();

						var month = d.getMonth()+1;
						var day = d.getDate();
						var output = d.getFullYear()+'-'+ (month<10 ? '0' : '')+month+'-'+(day<10 ? '0' : '')+day;
						
						$('#exportInvData').attr("href","/exportInvData/"+output+"/"+output);
						$('#inventoryListReport').DataTable({
							ajax: "inventoryReportData/"+output+'/'+output,
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
							'copy', 'csv', 'print'
							],
							'columns': [
							{ 'data': 'code' },
							{ 'data': 'name' },
							{ 'data': 'category' },
							{ 'data': 'uname' },
							{ 'data': 'Quantity' },
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
							{'data':'Date'},
							{ 
								"className": 'options',
								"data":    null,
								"render": function(data, type, full, meta){
									var valueHere=data.invId;

									return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
								}
							},
							]
						});

						$('#sortDate').click(function(){

							var dateStart = $('#dateStart').val();
							var dateEnd = $('#dateEnd').val()

							if(dateStart == '' || dateEnd == '')
							{
								var title   = 'Check Dates!',
								message = "Invalid Date Input!",
								type    = 'warning',
								options = {};

								toastr[type](message, title, options);
							}
							else
							{
								var title   = 'Report Sorted!',
								message = "Your data has been updated!",
								type    = 'success',
								options = {};

								toastr[type](message, title, options);
								$('#exportInvData').attr("href","/exportInvData/"+dateStart+"/"+dateEnd);
								$('#inventoryListReport').DataTable({
									ajax: "inventoryReportData/"+dateStart+'/'+dateEnd,
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
									'copy', 'csv', 'print'
									],
									'columns': [
									{ 'data': 'code' },
									{ 'data': 'name' },
									{ 'data': 'category' },
									{ 'data': 'uname' },
									{ 'data': 'Quantity' },
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
									{'data':'Date'},
									{ 
										"className": 'options',
										"data":    null,
										"render": function(data, type, full, meta){
											var valueHere=data.invId;

											return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
										}
									},
									]
								});
							}
						});





					</script>

				</tbody>
			</table>
		</div>
	</div>
	
</div>



