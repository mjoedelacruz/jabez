	<div class="tab-pane fade" id="profile-13">

		<div class="card">
			<div class="card-body">

				<div class="col-md-2 form-group">
					<input placeholder="Date Start"   id="dateStartGDetails" class="form-control-sm form-control" type="text"><p><i style="color: #029acf;">Date Start</i></p>
				</div>

				<div class="col-md-2 form-group">
					<input value="" placeholder="Date End"  id="dateEndGDetails" class="form-control-sm form-control" type="text"><p><i style="color: orange;">Date End</i></p>
				</div>
				<div id="bpDiv" class="col-md-3 form-group">
					<!-- <label>Choose Inventory Item</label> -->
					<select id="BpCodeGEReport" class="form-control" >
						<option value="ALL">- ALL -</option>
						@foreach($bp as $b)
						<option value="{{$b->bpCode}}">{{$b->name}}</option>
						@endforeach
					</select>
					<p><i style="color: green;">Supplier</i></p>
				</div>

				<div class="col-md-2 form-group" >
					<button id="sortDateGDetails" class="btn btn-success">Sort Report</button>
					<!-- <p><i><a id="exportGoodsEntryDetails" style="color: #029acf;"><span class="icon icon-download"></span> Download Report</a></i></p> -->
				</div>
			</div>
		</div>

		<div class='card'>
			<div class="card-body">
				<table id="GEDetailsReport" class="table table-bordered table-hover" style="width:100%;">
					<thead>
						<tr>
							<th width='5%'>#</th>
							<th width='5%'>SUPPLIER</th>
							<th width='5%'>USER</th>
							<th width='15%'>ITEM</th>
							<th width='5%'>QTY</th>
							<th width='5%'>UOM</th>
							<th width='5%'>PRICE</th>
							<th width='5%'>TOTAL</th>
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
							$('#exportGoodsEntryDetails').attr("href","/exportGoodsEntryDetails/"+output+"/"+output+'/ALL');	
							$('#GEDetailsReport').DataTable({
								ajax: "GEDetailsReport/"+output+'/'+output+'/ALL',
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
								{ 'data': 'goodsId' },
								{ 'data': 'bp' },
								{ 'data': 'uname' },
								{ 'data': 'itemName' },
								{ 'data':'qty'},
								{ 'data': 'uom' },
								{ 'data':'price'},
								{ 'data':'pay'},
								{ 'data':'Date'},
								]
							});

							$('#sortDateGDetails').click(function(){

								var dateStartGDetails = $('#dateStartGDetails').val();
								var dateEndGDetails = $('#dateEndGDetails').val();
								var BpCodeGEReport = $('#BpCodeGEReport').val();

								if(dateStartGDetails == '' || dateEndGDetails == '' || BpCodeGEReport == '')
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
									$('#exportGoodsEntryDetails').attr("href","/exportGoodsEntryDetails/"+dateStartGDetails+"/"+dateEndGDetails+'/'+BpCodeGEReport);
									
									$('#GEDetailsReport').DataTable({
										ajax: "GEDetailsReport/"+dateStartGDetails+'/'+dateEndGDetails+'/'+BpCodeGEReport,
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
										{ 'data': 'goodsId' },
										{ 'data': 'bp' },
										{ 'data': 'uname' },
										{ 'data': 'itemName' },
										{ 'data':'qty'},
										{ 'data': 'uom' },
										{ 'data':'price'},
										{ 'data':'pay'},
										{ 'data':'Date'},
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