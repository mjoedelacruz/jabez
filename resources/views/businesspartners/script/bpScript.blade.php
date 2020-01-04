<script>
$(document).ready(function(){

	jQuery(document).ready(function() {
		$('#bpStatus').select2({ dir: 'ltr' });
		$('#bpType').select2({ dir: 'ltr' });
		});
	
	//AUTO BP ID
	$.ajax({
		 		type:"GET",
		 		url: 'lastBpData',
		 		headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
		 		success: function (data){

		 			var bpId = data.bpData.id;
		 			var bp = (bpId + 1);
		 			//var newBpId = bp.substr(bp.length-4);
		 			$('#bpCode').val('BP-'+bp);

		 			// var s = "000" + n;
    		// 		return s.substr(s.length-4);
		 		}
			});

	$('#clearBp').click(function(){
		$('#bpCode').val('').prop('disabled',false);
		$('#bpName').val('');
		$('#bpPerson').val('');
		$('#bpContact').val('');
		$('#bpEmail').val('');
		$('#bpAddress').val('');
		$('#bpStatus').val('').select2({ dir: 'ltr' }).prop('selected',true);
		$('#bpType').val('').select2({ dir: 'ltr' }).prop({'checked':true,'disabled':false});
		$('#addBp').show().prop('disabled',false);
		$('#updateBp').hide();
		$.ajax({
		 		type:"GET",
		 		url: 'lastBpData',
		 		headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
		 		success: function (data){

		 			var bpId = data.bpData.id;
		 			var bp =(bpId + 1);
		 			//var newBpId = bp.substr(bp.length-4);
		 			$('#bpCode').val('BP-'+bp);

		 			// var s = "000" + n;
    		// 		return s.substr(s.length-4);
		 		}
			});
	});

	$('#addBp').click(function(){

		if( $('#bpCode').val() == "" || $('#bpName').val() == ""  || $('#bpStatus').val() == "" || $('#bpType').val() == "" )
		{
				var title   = 'Error!',
				message = 'Please check information',
				type    = 'error',
				options = {};

				toastr[type](message, title, options);
		}
		else
		{
			var bpInfo = {
				bpCode: $('#bpCode').val(),
				bpName: $('#bpName').val(),
				bpPerson: $('#bpPerson').val(),
				bpContact: $('#bpContact').val(),
				bpEmail: $('#bpEmail').val(),
				bpStatus:$('#bpStatus').val(),
				bpAddress: $('#bpAddress').val(),
				bpType: $('#bpType').val(),
				action: 1,
				};

			$.ajax({
					type:"POST",
					url: "{{route('businesspartners.store')}}",
					data: bpInfo,
					headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
					beforeSend: function() {
						$("#loadingState").show();
						$('#layout-main').fadeOut();
					},
					success: function (data){

						if(data == 1)
						{
								var title   = 'Data already exist!',
			 	  				message = "Please use other Code or Name!",
			 	  				type    = 'error',
			 	  				options = {};

			 	  				toastr[type](message, title, options);
							$("#loadingState").hide();
							$('#layout-main').fadeOut();
							return;
						}
						else
						{
								var title   = 'Data successfully added!',
			 	  				message = "Your data has been saved!",
			 	  				type    = 'success',
			 	  				options = {};

			 	  				toastr[type](message, title, options);
			 	  				$('#bpList').DataTable().ajax.reload();
								$("#loadingState").hide();
								$('#layout-main').fadeOut();
								$('#addBp').prop('disabled',true).hide();
								$('#updateBp').show();
								return;
						}
					}
				});
		}

	});

	$('#bpList tbody').on( 'click', 'button', function (){
			var id = $(this).val();

			$.ajax({
		 		type:"GET",
		 		url: 'showBpData/'+id,
		 		headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
		 		success: function (data){
		 			
		 			$('#bpCode').val(data.bpData.bpCode).prop('disabled',true);
		 			$('#bpId').val(data.bpData.id);
		 			$('#bpCode').val(data.bpData.bpCode);
					$('#bpName').val(data.bpData.name);
					$('#bpPerson').val(data.bpData.contactPerson);
					$('#bpContact').val(data.bpData.contactNo);
					$('#bpEmail').val(data.bpData.email);
					$('#bpStatus').val(data.bpData.status).select2({ dir: 'ltr' }).prop('selected',true);
					$('#bpType').val(data.bpData.type).select2({ dir: 'ltr' }).prop({'selected':true,'disabled':true});
					$('#bpAddress').val(data.bpData.address);
					$('#addBp').prop('disabled',true).hide();
					$('#updateBp').show();

	 		}


	 	});
			 window.scrollTo(0, 0);
		});

	$('#updateBp').click(function(){
				
		if( $('#bpCode').val() == "" || $('#bpName').val() == ""  || $('#bpStatus').val() == "" || $('#bpType').val() == "" )
		{
				var title   = 'Error!',
				message = 'Please check information',
				type    = 'error',
				options = {};

				toastr[type](message, title, options);
		}
		else
		{
			var bpInfo = {
				bpId: $('#bpId').val(),
				bpCode: $('#bpCode').val(),
				bpName: $('#bpName').val(),
				bpPerson: $('#bpPerson').val(),
				bpContact: $('#bpContact').val(),
				bpEmail: $('#bpEmail').val(),
				bpStatus:$('#bpStatus').val(),
				bpType:$('#bpType').val(),
				bpAddress: $('#bpAddress').val(),
				action: 2,
				};

			$.ajax({
					type:"POST",
					url: "{{route('businesspartners.store')}}",
					data: bpInfo,
					headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
					beforeSend: function() {
						$("#loadingState").show();
						$('#layout-main').fadeOut();
					},
					success: function (data){

						if(data == 1)
						{
								var title   = 'Data already exist!',
			 	  				message = "Please use other Code or Name!",
			 	  				type    = 'error',
			 	  				options = {};

			 	  				toastr[type](message, title, options);
							$("#loadingState").hide();
							$('#layout-main').fadeOut();
							return;
						}
						else
						{
								var title   = 'Data successfully updated!',
			 	  				message = "Your data has been saved!",
			 	  				type    = 'success',
			 	  				options = {};

			 	  				toastr[type](message, title, options);
			 	  				$('#bpList').DataTable().ajax.reload();
								$("#loadingState").hide();
								$('#layout-main').fadeOut();
								$('#addBp').prop('disabled',true).hide();
								$('#updateBp').show();
								return;
						}
					}
				});
		}
	});

});
</script>