<script type="text/javascript">
	$(document).ready(function(){

		jQuery(document).ready(function() {
			$('#itemCategory').select2({ dir: 'ltr' });
			$('#itemUom').select2({ dir: 'ltr' });
			$('#itemStatus').select2({ dir: 'ltr' });
		});

		//AUTO INV ID

		$.ajax({
			type:"GET",
			url: 'lastInvData',
			headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
			success: function (data){
				var invId = data.invData.id;
				var inv = (invId + 1);
		 			//var newInvId = inv.substr(inv.length-4);
		 			$('#itemCode').val('I-'+inv);
		 		}
		 		
		 	});

		$("#addCategory").click(function(){
			$('#catDiv').hide();
			$('#catDiv1').show();

		});

		$("#cancelCategory").click(function(){
			$('#catDiv1').hide();
			$('#catDiv').show();
			$('#itemCategoryText').val('');
		});

		$('#addUom').click(function(){
			$('#itemUomDiv').show();
			$('#uomDiv').hide();
		});

		$('#cancelUom').click(function(){
			$('#itemUomDiv').hide();
			$('#uomDiv').show();
			$('#itemUomText').val('');
		});

//
$('#itemCode').keyup(function() {
	if($(this).val() != '') {
		$('#addItem').prop('disabled', false);
		$('#activeAddItem').prop('disabled',false);


	}
});

		//ENABLE
		$('#activeAddItem').click(function(){
			//var d = new Date();
			 //$('#inventory').reload();
			 var x = $('#itemCodeVal').val();

			 $('#addItem').show().prop('disabled',false);
			 $('#itemCode').val(x).prop('disabled',false);
			 $('#updateItem').hide();
			 $('#itemId').val('');
			 $('#itemStore').val('')
			 $('#itemQty').val('');
			 $('#itemCode').val('');
			 $('#itemName').val('');
			 $('#itemCategoryText').val('');
			 $('#itemUomText').val('');
			 $('#itemSellingPrice').val('');
			 $('#itemCategory').val('0').select2({dir:'ltr'}).prop('selected',true);
			 $('#itemUom').val('0').select2({dir:'ltr'}).prop('selected',true);
			 $('#itemStatus').val('0').select2({dir:'ltr'}).prop('selected',true);
			 $.ajax({
			 	type:"GET",
			 	url: 'lastInvData',
			 	headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
			 	success: function (data){
			 		var invId = data.invData.id;
			 		var inv = (invId + 1);
		 			//var newInvId = inv.substr(inv.length-4);
		 			$('#itemCode').val('I-'+inv);
		 		}
		 		
		 	});
			});



		//START ADD ITEM
		$("#addItem").click(function(){
			//$('#addItem').disabled()
			
			
			
			//alert($('#itemSellingPrice').val());
			var itemInfo = {
				itemName: $("#itemName").val(),
				itemCategory: $("#itemCategory").val(),
				itemCategoryName: $('#itemCategoryText').val(),
				itemCode: $("#itemCode").val(),
				itemUom: $("#itemUom").val(),
				uomText: $("#itemUomText").val(),
				itemStore: $('#itemStore').val(),
				//itemSellingPrice: $("#itemSellingPrice").val(),
				itemStatus: $('#itemStatus').val(),
				action: 1,
			};

			if($("#itemName").val() == "" || ($("#itemCategory").val() == 0 && $("#itemCategoryText").val() == "") || ($("#itemUom").val() == 0 && $("#itemUomText").val() == "") ){
				var title   = 'Error!',
				message = 'Please check information',
				type    = 'error',
				options = {};

				toastr[type](message, title, options);
			} 
			else
			{
				$.ajax({
					type:"POST",
					url: "{{route('inventory.store')}}",
					data: itemInfo,
					headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
					beforeSend: function() {
						$("#loadingState").show();
						$('#layout-main').fadeOut();
					},
					success: function (data){

						$('#catDiv1').hide();
						$('#catDiv').show();
						$('#itemUomDiv').hide();
						$('#uomDiv').show();
						
						$('#updateItem').show();

						if(data == 1)
						{
							var title   = 'Invalid Entry!',
							message = "Item Name/Code already exist!",
							type    = 'warning',
							options = {};

							toastr[type](message, title, options);
			 	  				//$('#itemGroupData').DataTable().ajax.reload();
			 	  				$("#loadingState").hide	();
			 	  				return;

			 	  			}
			 	  			else
			 	  			{
			 	  				var title   = 'Item successfully saved!',
			 	  				message = "Your data has been updated!",
			 	  				type    = 'success',
			 	  				options = {};

			 	  				toastr[type](message, title, options);
			 	  				$('#inventoryList').DataTable().ajax.reload();
			 	  				//$('#saveItem').prop('disabled',true);
			 	  				$("#loadingState").hide();
			 	  				$('#layout-main').fadeIn();
			 	  				var x = $('#itemCodeVal').val();

			 	  				$('#addItem').show().prop('disabled',false);
			 	  				$('#itemCode').val(x).prop('disabled',false);
			 	  				$('#updateItem').hide();
			 	  				$('#itemId').val('');
			 	  				$('#itemCategoryText').val('');
			 	  				$('#itemUomText').val('');
			 	  				$('#itemQty').val('');
			 	  				$('#itemCode').val('');
			 	  				$('#itemName').val('');
			 	  				$('#itemSellingPrice').val('');
			 	  				$('#itemStore').val('')
			 	  				$('#itemCategory').val('0').select2({dir:'ltr'}).prop('selected',true);
			 	  				$('#itemUom').val('0').select2({dir:'ltr'}).prop('selected',true);
			 	  				$('#itemStatus').val('0').select2({dir:'ltr'}).prop('selected',true);
			 	  				$.ajax({
			 	  					type:"GET",
			 	  					url: 'lastInvData',
			 	  					headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
			 	  					success: function (data){
			 	  						var invId = data.invData.id;
			 	  						var inv = (invId + 1);
			 	  						$('#itemCode').val('I-'+inv);
			 	  					}

			 	  				});


			 	  			}

			 	  			var options = "<option value='0'>--Select Category--</option>";

			 	  			for(var i = 0; i < data.categories.length; i++){
			 	  				options +="<option value='"+data.categories[i]["id"]+"'>"+data.categories[i]["name"]+"</option>";
			 	  				$('#itemCategory').html(options);
			 	  			}	


			 	  			var options2 = "<option value='0'>--Select UoM--</option>";

			 	  			for(var i = 0; i < data.uoms.length; i++){
			 	  				options2 +="<option value='"+data.uoms[i]["id"]+"'>"+data.uoms[i]["name"]+"</option>";
			 	  				$('#itemUom').html(options2);
			 	  			}

			 	  			$('#itemCategory').val().select2({ dir: 'ltr' }).prop('selected',true);
			 	  			$('#itemUom').val().select2({ dir: 'ltr' }).prop('selected',true);
			 	  			return;
			 	  			


			 	  		}

			 	  	});
			}

		});
		//END ADD ITEM

		//START DATA UPDATE
		$('#inventoryList tbody').on( 'click', 'button', function (){
			var id = $(this).val();

		 	//alert(id);

		 	$.ajax({
		 		type:"GET",
		 		url: 'showInvData/'+id,
		 		headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
		 		success: function (data){
		 			// alert(data.itemList.icode);
		 			$('#itemId').val(data.itemList.itemId);
		 			$('#itemCode').val(data.itemList.icode).prop('disabled',true);
		 			$('#itemName').val(data.itemList.itemName);
		 			$('#itemSellingPrice').val(data.itemList.price);
		 			$('#itemQty').val(data.itemList.qty);
		 			$('#itemStore').val(data.itemList.sd);
	 			//$('#itemCategory').val(data.itemList.invCategoryId);
	 			$('#itemCategory').val(data.itemList.invCategoryId).select2({dir:'ltr'}).prop('selected',true);
	 			$('#itemUom').val(data.itemList.uomId).select2({dir:'ltr'}).prop('selected',true);
	 			$('#itemStatus').val(data.itemList.status).select2({dir:'ltr'}).prop('selected',true);
	 			$('#addItem').hide();
	 			$('#updateItem').show();

	 		}


	 	});

		 	window.scrollTo(0, 0);
		 });

		$("#updateItem").click(function(){
		 	//alert($('#itemCode').val());

		 	var itemInfo = {
		 		itemId: $('#itemId').val(),
		 		itemName: $("#itemName").val(),
		 		itemCategory: $("#itemCategory").val(),
		 		itemCategoryName: $('#itemCategoryText').val(),
		 		itemCode: $("#itemCode").val(),
		 		itemUom: $("#itemUom").val(),
		 		uomText: $("#itemUomText").val(),
		 		itemStore: $('#itemStore').val(),
				//itemSellingPrice: $("#itemSellingPrice").val(),
				itemStatus: $('#itemStatus').val(),
				action: 2,
			};


			if($("#itemName").val() == "" || ($("#itemCategory").val() == 0) || ($("#itemUom").val() == 0)){
				var title   = 'Error!',
				message = 'Please check information',
				type    = 'error',
				options = {};

				toastr[type](message, title, options);
			} 
			else
			{

				$.ajax({
					type:"POST",
					url: "{{route('inventory.store')}}",
					data: itemInfo,
					headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
					beforeSend: function() {
						$("#loadingState").show();
						$('#inventory').fadeOut();
					},
					success: function (data){
						$('#catDiv1').hide();
						$('#catDiv').show();
						$('#itemUomDiv').hide();
						$('#uomDiv').show();
						$("#itemUomText").val('');
						$('#itemCategoryText').val('');

						var title   = 'Item successfully saved!',
						message = "Your data has been updated!",
						type    = 'success',
						options = {};

						toastr[type](message, title, options);
						$('#inventoryList').DataTable().ajax.reload();
			 	  				//$('#saveItem').prop('disabled',true);
			 	  				$("#loadingState").hide();
			 	  				$('#inventory').fadeIn();
			 	  				return;



			 	  			}

			 	  		});
			}
		});

		

	});
</script>