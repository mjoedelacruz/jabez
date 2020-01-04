<script type="text/javascript">
	$(document).ready(function(){

		jQuery(document).ready(function() {
			$('#settleInvItem').select2({ dir: 'ltr' });
		});

		var count = 0;
		$('#settleInvItem').on('change', function() {
			var itemCode = this.value;
			//alert(itemCode);
			$.ajax({
				type:"GET",
				url: 'invCountItem/'+itemCode,
				headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
				success: function (data){
					// alert(data.inv.invCode);
					var invQty = data.inv.invQty;
					if(invQty==null)
					{
						invQty = 0;
					}
					else
					{
						invQty = $.number(invQty,2);
					}
					var invCountItem = '<tr id="settleInvRow'+count+'"><td><input style="width:100%;" id="settleItemCode'+count+'" value="'+data.inv.invCode+'" class="settleItemCode form-control" disabled=""></td><td><input style="width:100%;" id="settleItemName'+count+'" value="'+data.inv.invName+'" class="settleItemName form-control" disabled=""></td><td><input style="width:100%;" id="settleCat'+count+'" value="'+data.inv.icName+'" class="settleCat form-control" disabled=""></td><td><input style="width:100%;" id="settleUom'+count+'" value="'+data.inv.uName+'" class="settleUom form-control" disabled=""></td><td><input style="width:100%;" id="settleQty'+count+'" value="'+data.inv.invQty+'" class="settleQty form-control" disabled="" ></td><td><input style="width:100%;" id="settleNewQty'+count+'" class="settleNewQty form-control"></td><td><button   data-id="1" value="settleInvRow'+count+'" class="btn btn-danger btn-icon sq-24" type="button"><span class="icon icon-trash"></span></button></td></tr>';

					$("#settleInvList tbody").append(invCountItem);
					count++;

					var count1 = $('#settleRowList tr').length;
					// var count2 = count1 - 1;
					// $('#totalRowCount').val(count2);
				}

			});
		});

		//CLEAR INPUTS TABLE
		$('#clearTable').click(function(){
			$("#settleInvList > tbody").empty();
			$('#settleRemarks').val('');
			$('#settleInvItem').val('').select2({ dir: 'ltr' }).prop('selected',true);

		});

		//Remove Row
		$('#settleInvList tbody').on( 'click', 'button', function (){
			var settleInvRow = this.value;
			$('#'+settleInvRow+'').remove();
		});
		//Remove Row


		$('#setInvQty').click(function(){
			// alert('ok');

			var itemCode = [];
			var itemQty = [];
			var newItemQty = [];

			itemCodex = $('#settleInvList').find('.settleItemCode');
			itemQtyx = $('#settleInvList').find('.settleQty');
			newItemQtyx = $('#settleInvList').find('.settleNewQty');

			itemCodex.each(function(){
				itemCode.push($(this).val());
			});
			itemQtyx.each(function(){
				itemQty.push($(this).val());
			});
			newItemQtyx.each(function(){
				newItemQty.push($(this).val());
			});

			var settleRowList =
			{
				itemCode: itemCode,
				itemQty: itemQty,
				newItemQty: newItemQty,
				action: 1,
				settleRemarks: $('#settleRemarks').val(),
			}

			if( $('#settleRowList tr').length == 1 || $('#settleRemarks').val() == '' || $('#settleInvItem').val() == '')
			{
				var title   = 'Please Check Inputs!',
				message = "Your data is invalid!",
				type    = 'warning',
				options = {};

				toastr[type](message, title, options);
			}
			else
			{
				$.ajax({
					type:"POST",
					url: "{{route('inventory.saveSettleInvQty')}}",
					data: settleRowList,
					headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
					beforeSend: function() {
						$("#loadingState").show();
						$('#layout-main').fadeOut();
					},
					success: function (data){

						var title   = 'Goods successfully entered!',
						message = "Your inventory has been updated!",
						type    = 'success',
						options = {};

						toastr[type](message, title, options);
	 	  				//$('#gEntryList').DataTable().ajax.reload();
	 	  				$("#loadingState").hide();
	 	  				$("#settleInvList > tbody").empty();
	 	  				$('#settleRemarks').val('');
	 	  				$('#settleInvItem').val('').select2({ dir: 'ltr' }).prop('selected',true);
	 	  				$('#invCountList').DataTable().ajax.reload();
	 	  			}
	 	  		});
			}

		});	


		$('#invCountList tbody').on( 'click', 'button', function (){
			var id = $(this).val();
			
			$.ajax({
				type:"GET",
				url: 'invCountDetails/'+id,
				headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
				success: function (data){
					var tr = '<tr><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
					//alert(data.icList.id);
					for(var i = 0; i < data.icList.length; i++){
						tr +='<tr><td><input class="form-control" disabled="" value="'+data.icList[i]["icId"]+'"/></td><td><input class="form-control" disabled="" value="'+data.icList[i]["invCode"]+'"/></td><td><input class="form-control" disabled="" value="'+data.icList[i]["invName"]+'"/></td><td><input class="form-control" disabled="" value="'+data.icList[i]["invQty"]+'"/></td><td><input class="form-control" disabled="" value="'+data.icList[i]["newInvQty"]+'"/></td><td><input class="form-control" disabled="" value="'+data.icList[i]["qD"]+'"/></td></tr>';
						$("#invCountDetailsList tbody").html(tr);
					}	

					$('#invCountList').DataTable().ajax.reload();
				}
			});


		});

	});
</script>