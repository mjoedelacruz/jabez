	<div class="tab-pane fade" id="profile-15">
		
		<div class="card" >
			<div class="card-body">
				<div class="row">
					<div class="col-md-2 form-group">
						<input placeholder="Date Start"   id="dateStartSD" class="form-control-sm form-control" type="text"><p><i style="color: #029acf;">Date Start</i></p>
					</div>

					<div class="col-md-2 form-group">
						<input value="" placeholder="Date End"  id="dateEndSD" class="form-control-sm form-control" type="text"><p><i style="color: orange;">Date End</i></p>
					</div>

					<div id="bpDiv" class="col-md-2 form-group">
						<select id="BpCodeSDReports" class="form-control" >
							<option value="ALL">- ALL -</option>
							@foreach($cus as $c)
							<option value="{{$c->id}}">{{$c->name}}</option>
							@endforeach
						</select>
						<p><i style="color: green;">Customer</i></p>
					</div>
					<div class="col-md-2 form-group" >
						<button id="sortDateSD" class="btn btn-success">Sort Report</button>
						<!-- <p><i><a id="exportSalesDetailsData" style="color: #029acf;"><span class="icon icon-download"></span> Download Report</a></i></p> -->
					</div>
					<div class="col-md-2 form-group">
						<input value="" placeholder="Date End"  id="tPad" class="form-control-sm form-control" type="text" disabled=""><p><i style="color: red;">Total Amount</i></p>
					</div>
					<div class="col-md-2 form-group">
						<input value="" placeholder="Date End"  id="tVat" class="form-control-sm form-control" type="text" disabled=""><p><i style="color: red;">Total VAT</i></p>
					</div>
				</div>
			</div>
		</div>

		<div class="card">
			<div class="card-header">
				<b>SALES DETAIL LIST</b>
			</div>
			<div class="card-body">
				<table id="salesDetailsReport" class="table table-bordered table-hover" style="width:100%;">
					<thead>
						<tr>
							<th>#</th>
							<th>Remarks</th>
							<th>Code</th>
							<th>Menu Name</th>
							<th>Customer</th>
							<th width='10%'>Qty</th>
							<th width='10%'>Price</th>
							<th width='10%'>Total</th>
							<th>Discount</th>
							<th width='10%'>Vat Sales</th>
							<th width='10%'>12% Vat</th>
							<th width='10%'>Date</th>
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
								url: "showSalesDetails/"+output+'/'+output+'/ALL',
								headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
								success: function (data){
									$('#tPad').val(data.totalSales.rec);
									$('#tVat').val(data.totalVat.tax);
								}
							});

							$('#salesDetailsReport').DataTable({
								ajax: "showSalesDetails/"+output+'/'+output+'/ALL',
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
									{ 'data': 'sCode'},
									{ 
										"data":    null,
										"render": function(data, type, full, meta){
											var status=data.status;
											var disc = data.disc;

											if(status==1 && disc != null)
											{
												return ' <span class="label label-outline-info" width="100px">'+disc+'%</span> | <span class="label label-outline-primary" width="100px">VOIDED</span> ' ;
											}
											if(status==0 && disc != null)
											{
												return '<span class="label label-outline-info" width="100px">'+disc+'%</span>';
											}
											if(status==0 && disc == null)
											{
												return 'NONE';
											}

										}
									},
									{ 'data': 'imlCode' },
									{ 'data': 'miName' },
									{ 'data': 'bpName' },
									{ 'data': 'qty' },
									{ 'data': 'price' },
									{ 'data': 'rec' },
									{ 
										"data":    null,
										"render": function(data, type, full, meta){
											var dPrice=data.dPrice;
											var disc = data.disc;
											var price = data.price;
											var qty = data.qty

											if(dPrice!=null)
											{
												return dPrice;										}
												else
												{
													return 'NONE';
												}


											}
										},
										{ 
											"data":    null,
											"render": function(data, type, full, meta){
												var dPrice=data.dPrice;
												var disc = data.disc;
												var price = data.price;
												var qty = data.qty;
												var svat = data.svat;

												if(dPrice!=null)
												{
										//return	parseFloat(Math.round(dPrice/1.12).toFixed(2);
										return $.number(dPrice/1.12,2);										}
										else
										{
											return svat;
										}
										
										
									}
								},
								{ 
									"data":    null,
									"render": function(data, type, full, meta){
										var dPrice=data.dPrice;
										var disc = data.disc;
										var price = data.price;
										var qty = data.qty

										if(dPrice!=null)
										{
											return $.number((dPrice-(dPrice/1.12)),2);					
										}
										else
										{
											return data.vat;
										}
										
										
									}
								},
								{ 'data': 'Date' },
								
								]
							});

							$('#sortDateSD').click(function(){

								var dateStartSD = $('#dateStartSD').val();
								var dateEndSD = $('#dateEndSD').val();
								var BpCodeSDReports = $('#BpCodeSDReports').val();

								if(dateStartSD == '' || dateEndSD == '' || BpCodeSDReports == '')
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
									// $('#exportSalesData').attr("href","/exportSalesData/"+dateStartS+"/"+dateEndS+'/'+BpCodeSReports);

									$.ajax({
										type:"GET",
										url: "showSalesDetails/"+dateStartSD+'/'+dateEndSD+'/'+BpCodeSDReports,
										headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
										success: function (data){
											$('#tPad').val(data.totalSales.rec);
											$('#tVat').val(data.totalVat.tax);
										}
									});

									$('#salesDetailsReport').DataTable({
										ajax: "showSalesDetails/"+dateStartSD+'/'+dateEndSD+'/'+BpCodeSDReports,
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
										{ 'data': 'sCode'},
										{ 
											"data":    null,
											"render": function(data, type, full, meta){
												var status=data.status;
												var disc = data.disc;

												if(status==1 && disc != null)
												{
													return ' <span class="label label-outline-info" width="100px">'+disc+'%</span> | <span class="label label-outline-primary" width="100px">VOIDED</span> ' ;
												}
												if(status==0 && disc != null)
												{
													return '<span class="label label-outline-info" width="100px">'+disc+'%</span>';
												}
												if(status==0 && disc == null)
												{
													return 'NONE';
												}

											}
										},
										{ 'data': 'imlCode' },
										{ 'data': 'miName' },
										{ 'data': 'bpName' },
										{ 'data': 'qty' },
										{ 'data': 'price' },
										{ 'data': 'rec' },
										{ 
											"data":    null,
											"render": function(data, type, full, meta){
												var dPrice=data.dPrice;
												var disc = data.disc;
												var price = data.price;
												var qty = data.qty

												if(dPrice!=null)
												{
													return dPrice;										}
													else
													{
														return 'NONE';
													}


												}
											},
											{ 
												"data":    null,
												"render": function(data, type, full, meta){
													var dPrice=data.dPrice;
													var disc = data.disc;
													var price = data.price;
													var qty = data.qty;
													var svat = data.svat;

													if(dPrice!=null)
													{
										//return	parseFloat(Math.round(dPrice/1.12).toFixed(2);
										return $.number(dPrice/1.12,2);										}
										else
										{
											return svat;
										}
										
										
									}
								},
								{ 
									"data":    null,
									"render": function(data, type, full, meta){
										var dPrice=data.dPrice;
										var disc = data.disc;
										var price = data.price;
										var qty = data.qty

										if(dPrice!=null)
										{
											return $.number((dPrice-(dPrice/1.12)),2);					
										}
										else
										{
											return data.vat;
										}
										
										
									}
								},
								{ 'data': 'Date' },
								
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