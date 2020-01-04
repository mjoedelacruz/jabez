<script type="text/javascript">
	$(document).ready(function(){

		jQuery(document).ready(function() {
			$('#gEntryitemCode').select2({ dir: 'ltr' });
			$('#gEntryBpCode').select2({ dir: 'ltr' });
		});
		var count = 0;
		
		$('#gEntryitemCode').on('change', function() {
			var itemCode = this.value;
			
			$.ajax({
				type:"GET",
				url: 'selectItemCode/'+itemCode,
				headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
				success: function (data){

		 			// alert(data.itemList.icode);
		 			var itemCodeList = '<tr id="itemCodeRow'+count+'"><td><input style="width:100%;" id="itemCode'+count+'" value="'+data.itemCodeList.icode+'" class="itemCode form-control" disabled=""></td><td><input style="width:100%;" value="'+data.itemCodeList.itemName+'" id="itemName'+count+'" class="itemName form-control" disabled="" ></td><td><input style="width:100%;" id="itemQty'+count+'" class="itemQty form-control" ></td><td><input value="'+data.itemCodeList.uom+'" style="width:100%;" id="itemUom'+count+'" class="itemUom form-control" disabled=""></td><td><input value="" style="width:100%;" id="itemPrice'+count+'" class="itemPrice form-control" ></td><td><input value="'+data.itemCodeList.sd+'" style="width:100%;" id="itemSd'+count+'" class="itemSd form-control" disabled=""></td><td><button   data-id="1" value="itemCodeRow'+count+'" class="btn btn-danger btn-icon sq-24" type="button"><span class="icon icon-trash"></span></button></td></tr>';

		 			$("#itemCodeList tbody").append(itemCodeList);
		 			count++;

		 			var count1 = $('#itemRowList tr').length;
		 			var count2 = count1 - 1;
		 			$('#totalRowCount').val(count2);



		 		}

		 	});	

		});
		//gEntryItemCode



		//CLEAR INPUTS TABLE
		$('#clearEntry').click(function(){
			$("#itemCodeList > tbody").empty();
			$('#gEntryBpCode').val('').select2({ dir: 'ltr' }).prop({'selected':true,'disabled':false});
			$('#gEntryitemCode').val('').select2({ dir: 'ltr' }).prop({'selected':true,'disabled':false});
			$('#addGoods').show();
			$('#returnGoods').hide();
			$('#geTitle').html('GOODS ENTRY');
		});

		//Remove Row
		$('#itemCodeList tbody').on( 'click', 'button', function (){
			var itemCodeRow = this.value;
			$('#'+itemCodeRow+'').remove();
		});
		//Remove Row

		//Save Goods Entry
		$('#addGoods').click(function(e){

			var itemCode = [];
			var itemName = [];
			var itemQty = [];
			var itemUom = [];
			var itemPrice = [];

			itemCodex = $('#itemCodeList').find('.itemCode');
			itemNamex = $('#itemCodeList').find('.itemName');
			itemQtyx = $('#itemCodeList').find('.itemQty');
			itemUomx = $('#itemCodeList').find('.itemUom');
			itemPricex = $('#itemCodeList').find('.itemPrice');


			itemCodex.each(function(){
				itemCode.push($(this).val());
			});
			itemNamex.each(function(){
				itemName.push($(this).val());
			});
			itemQtyx.each(function(){
				itemQty.push($(this).val());
			});
			itemUomx.each(function(){
				itemUom.push($(this).val());
			});
			itemPricex.each(function(){
				itemPrice.push($(this).val());
			});

			//alert(itemCode);
			var itemRowList =
			{
				itemCode:itemCode,
				itemName:itemName,
				itemUom:itemUom,
				itemQty:itemQty,
				itemPrice:itemPrice,
				action:1,
				bpCode: $('#gEntryBpCode').val(),

			};

			if( $('#gEntryBpCode').val() == '' || $('#totalRowCount').val() == 0 || itemPrice == '' || itemQty == '')
			{
				var title   = 'Please Select BP and Items!',
				message = "Please check inputs!",
				type    = 'warning',
				options = {};

				toastr[type](message, title, options);
			}
			else
			{
				$.ajax({
					type:"POST",
					url: "{{route('goodsentry.store')}}",
					data: itemRowList,
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
						$('#gEntryList').DataTable().ajax.reload();
						$('#gRetList').DataTable().ajax.reload();
						$('#staleList').DataTable().ajax.reload();
						$("#loadingState").hide();
						$('#layout-main').fadeOut();
						$("#itemCodeList > tbody").empty();
						$('#gEntryBpCode').val('').select2({ dir: 'ltr' }).prop('selected',true);
						$('#gEntryitemCode').val('').select2({ dir: 'ltr' }).prop('selected',true);
					}
				});
			}

			

		});


//Modal Table Goods Entry
var counts = 0;
$('#staleList tbody').on( 'click', 'button', function (){
	var id = $(this).val();
	var dataId = $(this).attr("data-id");
	$.ajax({
		type:"GET",
		url: 'gEntryDetailList/'+id,
		headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
		success: function (data){
		 			//alert(data.goodsList.bp1);
		 			$('#bpNameS').html(data.goodsList.bp1);
		 			$('#geNum').html('# '+data.goodsList.id);
		 			$('#totalPay').val(data.goodsList.pay);
		 			$('#goodsEntryId').val(data.goodsList.id);
		 			$('#ge1').html('GOODS ENTRY');

		 			if((data.goodsList.status) == 2)
		 			{
		 				$('#cancelGE').prop('disabled',true).html('CANCELLED').removeClass('btn-danger').addClass('btn-primary');
		 			}
		 			else
		 			{
		 				$('#cancelGE').prop('disabled',false).html('Cancel Goods Entry').addClass('btn-danger').removeClass('btn-primary');
		 			}
		 			//alert(data.GEDetails.length);
		 			if(dataId == 1)
		 			{
		 				$('#gEntryBpCode').val(data.goodsList.bpCode).select2({ dir: 'ltr' }).prop({'selected':true,'disabled':true});
		 				$('#gEntryitemCode').prop('disabled',true);
		 				$('#addGoods').hide();
		 				$('#returnGoods').show();
		 				$('#geTitle').html('GOODS RETURN');
		 				var ged = "";
		 				for(var i = 0; i < data.GEDetails.length; i++){

		 					ged += '<tr id="geRow'+counts+'"><td><input style="width:100%;" id="geItemCode'+counts+'" value="'+data.GEDetails[i]["iCode"]+'" class="geItemCode form-control" disabled=""></td><td><input style="width:100%;" id="geItemName'+counts+'" value="'+data.GEDetails[i]["itemName"]+'" class="geItemName form-control" disabled=""></td><td><input style="width:100%;" id="geQty'+counts+'" value="'+data.GEDetails[i]["qty"]+'" class="geQty form-control"></td><td><input style="width:100%;" id="geUom'+counts+'" value="'+data.GEDetails[i]["uom"]+'" class="geUom form-control" disabled=""></td><td><input style="width:100%;" id="gePrice'+counts+'" value="'+data.GEDetails[i]["price"]+'" class="gePrice form-control" disabled="" ></td><td><input style="width:100%;" id="geSd'+counts+'" value="'+data.GEDetails[i]["sd"]+'" class="geSd form-control" disabled="" ></td><td><button   data-id="1" value="geRow'+counts+'" class="btn btn-danger btn-icon sq-24" type="button"><span class="icon icon-trash"></span></button></td></tr>';
		 					$("#itemCodeList tbody").html(ged);
		 				//$("#itemCodeList tbody").append(ged);

		 				counts++;
		 			}
		 			$("html, body").animate({
		 				scrollTop: 0
		 			}, 800);
		 		}
		 	}



		 });
})

$('#gEntryList tbody').on( 'click', 'button', function (){
	var id = $(this).val();
	var dataId = $(this).attr("data-id");
	$.ajax({
		type:"GET",
		url: 'gEntryDetailList/'+id,
		headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
		success: function (data){
		 			//alert(data.goodsList.bp1);
		 			$('#bpNameS').html(data.goodsList.bp1);
		 			$('#geNum').html('# '+data.goodsList.id);
		 			$('#totalPay').val(data.goodsList.pay);
		 			$('#goodsEntryId').val(data.goodsList.id);
		 			$('#ge1').html('GOODS ENTRY');

		 			if((data.goodsList.status) == 2)
		 			{
		 				$('#cancelGE').prop('disabled',true).html('CANCELLED').removeClass('btn-danger').addClass('btn-primary');
		 			}
		 			else
		 			{
		 				$('#cancelGE').prop('disabled',false).html('Cancel Goods Entry').addClass('btn-danger').removeClass('btn-primary');
		 			}
		 			//alert(data.GEDetails.length);
		 			if(dataId == 1)
		 			{
		 				$('#gEntryBpCode').val(data.goodsList.bpCode).select2({ dir: 'ltr' }).prop({'selected':true,'disabled':true});
		 				$('#gEntryitemCode').prop('disabled',true);
		 				$('#addGoods').hide();
		 				$('#returnGoods').show();
		 				$('#geTitle').html('GOODS RETURN');
		 				var ged = "";
		 				for(var i = 0; i < data.GEDetails.length; i++){

		 					ged += '<tr id="geRow'+counts+'"><td><input style="width:100%;" id="geItemCode'+counts+'" value="'+data.GEDetails[i]["iCode"]+'" class="geItemCode form-control" disabled=""></td><td><input style="width:100%;" id="geItemName'+counts+'" value="'+data.GEDetails[i]["itemName"]+'" class="geItemName form-control" disabled=""></td><td><input style="width:100%;" id="geQty'+counts+'" value="'+data.GEDetails[i]["qty"]+'" class="geQty form-control"></td><td><input style="width:100%;" id="geUom'+counts+'" value="'+data.GEDetails[i]["uom"]+'" class="geUom form-control" disabled=""></td><td><input style="width:100%;" id="gePrice'+counts+'" value="'+data.GEDetails[i]["price"]+'" class="gePrice form-control" disabled="" ></td><td><input style="width:100%;" id="geSd'+counts+'" value="'+data.GEDetails[i]["sd"]+'" class="geSd form-control" disabled="" ></td><td><button   data-id="1" value="geRow'+counts+'" class="btn btn-danger btn-icon sq-24" type="button"><span class="icon icon-trash"></span></button></td></tr>';
		 					$("#itemCodeList tbody").html(ged);
		 				//$("#itemCodeList tbody").append(ged);

		 				counts++;
		 			}
		 			$("html, body").animate({
		 				scrollTop: 0
		 			}, 800);
		 		}
		 	}



		 });

	$('#gEntryDetailList').DataTable({
		ajax: ("gEntryDetailList/"+id),
		'bDestroy'    : true,
		'paging'      : false,
		'lengthChange': true,
		'searching'   : false,
		'ordering'    : true,
		'info'        : false,
		'autoWidth'   : true,
		'responsive'  : true,

		'columns': [
		{ 'data': 'iCode' },
		{ 'data': 'itemName' },
		{ 'data': 'qty' },
		{ 'data': 'price' },
		{ 'data': 'pay' },

		]
	});

});
//Modal Table Goods Entry

//Modal Table Goods Return
$('#gRetList tbody').on( 'click', 'button', function (){
	var id = $(this).val();
	$.ajax({
		type:"GET",
		url: 'gReturnDetailList/'+id,
		headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
		success: function (data){
			$('#bpNameS').html(data.gr.bp);
			$('#geNum').html('# '+data.gr.id);
			$('#grNum').html('Based on Goods Entry # '+data.gr.geId);
			$('#totalPay').val(data.gr.amt);
			$('#goodsEntryId').val(data.gr.geId);
		}
	});
	
	
	$('#ge1').html('GOODS RETURN');
	
	$('#gEntryDetailList').DataTable({
		ajax: ("gReturnDetailList/"+id),
		'bDestroy'    : true,
		'paging'      : false,
		'lengthChange': true,
		'searching'   : false,
		'ordering'    : true,
		'info'        : false,
		'autoWidth'   : true,
		'responsive'  : true,

		'columns': [
		{ 'data': 'iCode' },
		{ 'data': 'invName' },
		{ 'data': 'qty' },
		{ 'data': 'price' },
		{ 'data': 'amt' },

		]
	});
});
//Modal Table Goods Return

			//Cancel Goods Entry
			$('#cancelGoods').click(function(e){
				//alert('yes');
				var i

				var cancelGoods =
				{
					action: 3,
					goodsEntryId: $('#goodsEntryId').val(),
				};

				$.ajax({
					type:"POST",
					url: "{{route('goodsentry.store')}}",
					data: cancelGoods,
					headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
					beforeSend: function() {
						$("#loadingState").show();
						$('#layout-main').fadeOut();
					},
					success: function (data){
						var title   = 'Entry has been cancelled!',
						message = "Your data has been updated!",
						type    = 'success',
						options = {};

						toastr[type](message, title, options);
						$('#gEntryList').DataTable().ajax.reload();
						$('#gRetList').DataTable().ajax.reload();
						$('#staleList').DataTable().ajax.reload();
						$("#loadingState").hide();
						$('#layout-main').fadeOut();
						//$("#itemCodeList > tbody").empty();
						//$('#gEntryBpCode').val('').select2({ dir: 'ltr' }).prop('selected',true);
						//$('#gEntryitemCode').val('').select2({ dir: 'ltr' }).prop('selected',true);
					}
				});

			});
			//Cancel Goods Entry		       	  

			//Return Goods
			$('#returnGoods').click(function(){
				var ok = $('#goodsEntryId').val();
				//alert(ok);
				$('#addGoods').show();
				$('#returnGoods').hide();

				var itemCode = [];
				var itemName = [];
				var itemQty = [];
				var itemUom = [];
				var itemPrice = [];

				itemCodex = $('#itemCodeList').find('.geItemCode');
				itemNamex = $('#itemCodeList').find('.geItemName');
				itemQtyx = $('#itemCodeList').find('.geQty');
				itemUomx = $('#itemCodeList').find('.geUom');
				itemPricex = $('#itemCodeList').find('.gePrice');

				itemCodex.each(function(){
					itemCode.push($(this).val());
				});
				itemNamex.each(function(){
					itemName.push($(this).val());
				});
				itemQtyx.each(function(){
					itemQty.push($(this).val());
				});
				itemUomx.each(function(){
					itemUom.push($(this).val());
				});
				itemPricex.each(function(){
					itemPrice.push($(this).val());
				});

				var returnGoods =
				{
					action: 4,
					goodsEntryId: $('#goodsEntryId').val(),
					itemCode: itemCode,
					itemName: itemName,
					itemQty: itemQty,
					itemUom: itemUom,
					itemPrice: itemPrice,
					bpCode: $('#gEntryBpCode').val(),
				};

				$.ajax({
					type:"POST",
					url: "{{route('goodsentry.store')}}",
					data: returnGoods,
					headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
					beforeSend: function() {
						$("#loadingState").show();
						$('#layout-main').fadeOut();
					},
					success: function (data){
						var title   = 'Your inventory was updated!',
						message = "Your data is now ready!",
						type    = 'success',
						options = {};

						toastr[type](message, title, options);
						$('#gEntryList').DataTable().ajax.reload();
						$('#gRetList').DataTable().ajax.reload();
						$('#staleList').DataTable().ajax.reload();
						$("#loadingState").hide();
						$('#layout-main').fadeOut();
						$("#itemCodeList > tbody").empty();
						$('#gEntryBpCode').val('').select2({ dir: 'ltr' }).prop({'selected':true,'disabled':false});
						$('#gEntryitemCode').val('').select2({ dir: 'ltr' }).prop({'selected':true,'disabled':false});
						$('#addGoods').show();
						$('#returnGoods').hide();
						$('#geTitle').html('GOODS ENTRY');
						//$("#itemCodeList > tbody").empty();
						//$('#gEntryBpCode').val('').select2({ dir: 'ltr' }).prop('selected',true);
						//$('#gEntryitemCode').val('').select2({ dir: 'ltr' }).prop('selected',true);
					}
				});
			});
		});
	</script>