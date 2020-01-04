<div class="tab-pane fade" id="profile-12">

	<div class="card">
		<div class="card-body">
			
			<div class="col-md-2 form-group">
				<input placeholder="Date Start" name="dateStarts"  id="dateStartGoods" class="form-control-sm form-control" type="text"><p><i style="color: #029acf;">Date Start</i></p>
			</div>

			<div class="col-md-2 form-group">
				<input value="" placeholder="Date End" name="dateEndGoods" id="dateEndGoods" class="form-control-sm form-control" type="text"><p><i style="color: orange;">Date End</i></p>
			</div>
			<div id="bpDiv" class="col-md-3 form-group">
				<!-- <label>Choose Inventory Item</label> -->
				<!-- <script>jQuery(document).ready(function() { $('#BpCodeReports').select2({dir:'ltr'}); });</script> -->
				<select id="BpCodeReports" class="form-control" >
					<option value="ALL">- ALL -</option>
					@foreach($bp as $b)
					<option value="{{$b->bpCode}}">{{$b->name}}</option>
					@endforeach
				</select>
				<p><i style="color: green;">Supplier</i></p>
			</div>
			
			<div class="col-md-2 form-group" >
				<button id="sortDateGoods" class="btn btn-success">Sort Report</button>
				<!-- <p><i><a id="exportGoodsEntry" style="color: #029acf;"><span class="icon icon-download"></span> Download Report</a></i></p> -->
			</div>

			<div class="col-md-2 form-group">
				<input value="" placeholder="Date End" name="dateEndGoods" id="totalGEpay" class="form-control-sm form-control" type="text" disabled=""><p><i style="color: red;">Total Amount</i></p>
			</div>
		</div>
	</div>
	

	<div class="card">
		<div class="card-body">
			<table id="GoodsEntryReport" class="table table-bordered table-hover" style="width:100%;">
				<thead>
					<tr>
						<th width='5%'>#</th>
						<th width='15%'>Supplier</th>
						<th width='10%'>User</th>
						<th width='5%'>Amount</th>
						<th width='5%'>Status</th>
						<th width='5%'>Date</th>
						<!-- <th width='5%'></th> -->
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
							url: "GoodsEntryData/"+output+'/'+output+'/ALL',
							headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
							success: function (data){
								$('#totalGEpay').val(data.totalPay.pay);
							}
						});
						$('#exportGoodsData').attr("href","/exportInvData/"+output+"/"+output+'/ALL');
						$('#GoodsEntryReport').DataTable({
							ajax: "GoodsEntryData/"+output+'/'+output+'/ALL',
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
							{ 'data': 'id' },
							{ 'data': 'bp' },
							{ 'data': 'user' },
							{ 'data': 'pay' },
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
							// { 
							// 	"className": 'options',
							// 	"data":    null,
							// 	"render": function(data, type, full, meta){
							// 		var valueHere=data.id;

							// 		return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
							// 	}
							// },
							]
						});

						$('#sortDateGoods').click(function(){

							var dateStartGoods = $('#dateStartGoods').val();
							var dateEndGoods = $('#dateEndGoods').val();
							var BpCodeReports = $('#BpCodeReports').val();
							if(dateStart == '' || dateEnd == '' || BpCodeReports == '')
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
								$('#exportGoodsEntry').attr("href","/exportGoodsEntry/"+dateStartGoods+"/"+dateEndGoods+'/'+BpCodeReports);
								$.ajax({
									type:"GET",
									url: "GoodsEntryData/"+dateStartGoods+'/'+dateEndGoods+'/'+BpCodeReports,
									headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
									success: function (data){
										$('#totalGEpay').val(data.totalPay.pay);
									}
								});
								$('#GoodsEntryReport').DataTable({
									ajax: "GoodsEntryData/"+dateStartGoods+'/'+dateEndGoods+'/'+BpCodeReports,
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
									{ 'data': 'id' },
									{ 'data': 'bp' },
									{ 'data': 'user' },
									{ 'data': 'pay' },
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
							// { 
							// 	"className": 'options',
							// 	"data":    null,
							// 	"render": function(data, type, full, meta){
							// 		var valueHere=data.id;

							// 		return '<button type="button" data-toggle="tooltip" title="" class="btn btn-sm btn-info" data-id="1" value="'+valueHere+'"><i class="icon icon-edit"></i></button>';
							// 	}
							// },
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