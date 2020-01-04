	<div class="tab-pane fade" id="profile-14">

		<div class="card" >
			<div class="card-body">
				<div class="row">
					<div class="col-md-2 form-group">
						<input placeholder="Date Start"   id="dateStartS" class="form-control-sm form-control" type="text"><p><i style="color: #029acf;">Date Start</i></p>
					</div>

					<div class="col-md-2 form-group">
						<input value="" placeholder="Date End"  id="dateEndS" class="form-control-sm form-control" type="text"><p><i style="color: orange;">Date End</i></p>
					</div>

					<div id="bpDiv" class="col-md-3 form-group">
						<select id="BpCodeSReports" class="form-control" >
							<option value="ALL">- ALL -</option>
							@foreach($cus as $c)
							<option value="{{$c->id}}">{{$c->name}}</option>
							@endforeach
						</select>
						<p><i style="color: green;">Customer</i></p>
					</div>
					<div class="col-md-2 form-group" >
						<button id="sortDateS" class="btn btn-success">Sort Report</button>
						<!-- <p><i><a id="exportSalesData" style="color: #029acf;"><span class="icon icon-download"></span> Download Report</a></i></p> -->
					</div>

					<div class="col-md-2 form-group">
						<input value="" placeholder="Date End" name="dateEndGoods" id="totalSales" class="form-control-sm form-control" type="text" disabled=""><p><i style="color: red;">Total Amount</i></p>
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<b>SALES REPORTS</b>
			</div>
			<div class="card-body">
				<table id="salesReport" class="table table-bordered table-hover" style="width:100%;">
					<thead>
						<tr>
							<th width="5%">#</th>
							<th width="5%">OS #</th>
							<th width="5%">Customer</th>
							<th width="5%">Waiter</th>
							<th width="5%">Discount Type</th>
							<!-- <th>Address</th> -->
							<th width="5%">Total Amount</th>
							<th>Discounted Amt</th>
							<th width="10%">Date</th>
							<!-- <th width="5%">ACTION</th> -->

						</tr>
					</thead>
					<tbody>

						<script>
							var d = new Date();
							var month = d.getMonth()+1;
							var day = d.getDate();
							var output = d.getFullYear()+'-'+ (month<10 ? '0' : '')+month+'-'+(day<10 ? '0' : '')+day;
							$.ajax({
								type:"GET",
								url: "showSalesData/"+output+'/'+output+'/ALL',
								headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
								success: function (data){
									$('#totalSales').val(data.totalSales.rec);
								}
							});

							$('#exportSalesData').attr("href","/exportSalesData/"+output+"/"+output+'/ALL');
							$('#salesReport').DataTable({
								ajax: "showSalesData/"+output+'/'+output+'/ALL',
								'dom': 'Bfrtip',
								'buttons': [
								'copy', 'csv', 'print'
								],
								'bDestroy'    : true,
								'paging'      : true,
								'lengthChange': true,
								'searching'   : true,
								'ordering'    : true,
								'info'        : true,
								'autoWidth'   : true,
								'responsive'  : true,
								
								'columns': [
								{ 'data': 'code' },
								{ 'data': 'os_no' },
								{ 'data': 'bp' },
								{ 'data': 'uName' },
								{ 
									"data":    null,
									"render": function(data, type, full, meta){
										var disc=data.disc;

										if(disc==0 || disc == null)
										{
											return '<span class="label label-outline-danger" width="100px">NO DISCOUNT</span>' ;
										}
										else
										{
											return '<span class="label label-outline-info" width="100px" >'+disc+'</span>' ;
										}


									}

								},
								{ 'data': 'tr'},
								{ 'data': 'pad'},
								{ 'data': 'date'},
								// { 
								// 	"className": 'options',
								// 	"data":    null,
								// 	"render": function(data, type, full, meta){
								// 		var valueHere=data.sId;

								// 		return '<button value="'+valueHere+'" class="btn btn-info" data-toggle="modal" data-target="#successModalAlertdb" type="button"><i class="icon icon-eye"></i></button>';
								// 	}

								// },
								]
							});

							$('#sortDateS').click(function(){

								var dateStartS = $('#dateStartS').val();
								var dateEndS = $('#dateEndS').val();
								var BpCodeSReports = $('#BpCodeSReports').val();

								if(dateStartS == '' || dateEndS == '' || BpCodeSReports == '')
								{
									var title   = 'Check Inputs!',
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
									$.ajax({
										type:"GET",
										url: "showSalesData/"+dateStartS+'/'+dateEndS+'/'+BpCodeSReports,
										headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
										success: function (data){
											$('#totalSales').val(data.totalSales.rec);
										}
									});
									$('#exportSalesData').attr("href","/exportSalesData/"+dateStartS+"/"+dateEndS+'/'+BpCodeSReports);

									$('#salesReport').DataTable({
										ajax: "showSalesData/"+dateStartS+'/'+dateEndS+'/'+BpCodeSReports,
										'bDestroy'    : true,
										'paging'      : true,
										'lengthChange': true,
										'searching'   : true,
										'ordering'    : true,
										'info'        : true,
										'autoWidth'   : true,
										'responsive'  : true,
										'dom': 'Bfrtip',
										'buttons': [
										'copy', 'csv', 'print'
										],
										'columns': [
										{ 'data': 'code' },
										{ 'data': 'os_no' },
										{ 'data': 'bp' },
										{ 'data': 'uName' },
										{ 
											"data":    null,
											"render": function(data, type, full, meta){
												var disc=data.disc;

												if(disc==0 || disc == null)
												{
													return '<span class="label label-outline-danger" width="100px">NO DISCOUNT</span>' ;
												}
												else
												{
													return '<span class="label label-outline-info" width="100px" >'+disc+'</span>' ;
												}
											}
										},
										{ 'data': 'tr'},
										{ 'data': 'pad'},
										{ 'data': 'date'},
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