<script>
	$(document).ready(function(){

//DASHBOARD DATA
		$.ajax({
			type:"GET",
			url: 'dashboardData',
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
			success: function (data){

					$('#totalSales').html(data.totalSales.sales);
					$('#totalInvoices').html(data.totalInvoices.invoice);
	
					// $('#sCode').html(data.saleList.sCode);
					// $('#bpSaleDetails').html(data.saleList.bp);
			
			}
		});
		

		$('#saleData tbody').on( 'click', 'button', function (){
			var id = $(this).val();

			$.ajax({
		 		type:"GET",
		 		url: 'showSalesDetails/'+id,
		 		headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
		 		success: function (data){

					$('#sCode').html(data.saleList.sCode);
					$('#bpSaleDetails').html(data.saleList.bp);
					$('#totalRec').val(data.totalRec.pad);

					
				}
			});

			$('#salesDetailsList').DataTable({
						ajax: ("showSalesDetails/"+id),
						'bDestroy'    : true,
						'paging'      : false,
						'lengthChange': true,
						'searching'   : false,
						'ordering'    : true,
						'info'        : false,
						'autoWidth'   : true,
						'responsive'  : true,

						'columns': [
						{ 'data': 'mCode' },
						{ 'data': 'mName' },
						{ 'data': 'qty' },
						{ 'data': 'price' },
						{
							"className": 'text-center',
							"data": null,
							"render": function (data, type, full, meta) {
								var dVal = data.dVal;
								if(dVal == 0 || dVal == null)
								{
									return 'NONE' ;
								}
								else
								{
									return dVal+'%';
								}

							}
						},
						{
							"className": 'text-center',
							"data": null,
							"render": function (data, type, full, meta) {
								var price = data.price;
								var qty = data.qty;

								var amt = data.amount;
								if(amt == null)
								{
									return price*qty ;
								}
								else
								{
									return amt;
								}

							}
						},

						]
					});

		});

	});
</script>