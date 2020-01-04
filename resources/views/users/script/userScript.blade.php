<script type="text/javascript">
	$(document).ready(function(){



		$('#saveUser').click(function(){
			$('#saveUser').prop('disabled',true)
			if( $('#fullName').val() == '' || $('#userName').val() == '' || $('#pWord').val() == '' || $('#pWord2').val() == '' || $('#uType').val() == '' || $('#uStatus') == '' )
			{
				var title   = 'Error!',
				message = 'Please check user information',
				type    = 'error',
				options = {};

				toastr[type](message, title, options);
				$('#saveUser').prop('disabled',false)

			}
			else
			{
				if( $('#pWord').val() == $('#pWord2').val() )
				{
					var userInfo =
					{
						fullName: $('#fullName').val(),
						uName: $('#userName').val(),
						pWord: $('#pWord').val(),
						uType: $('#uType').val(),
						uStatus: $('#uStatus').val(),
						action: 1,
					}
					$.ajax({
						type:"POST",
						url: "{{route('users.store')}}",
						data: userInfo,
						headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
						beforeSend: function() {
							$("#loadingState").show();
							$('#layout-main').fadeOut();
						},
						success: function (data){

							if(data=='error')
							{
								var title   = 'Error!',
								message = 'Username already exist!',
								type    = 'error',
								options = {};

								toastr[type](message, title, options);
								$("#loadingState").hide();
								$('#layout-main').fadeIn();
								$('#saveUser').prop('disabled',false)
							}
							else
							{
								var title   = 'User saved successfully!',
								message = 'User Data is ready!',
								type    = 'success',
								options = {};

								toastr[type](message, title, options);
								$('#userList').DataTable().ajax.reload();
								$("#loadingState").hide();
								$('#layout-main').fadeIn();
								$('#saveUser').prop('disabled',false);
								$('#fullName').val('');
								$('#userName').val('');
								$('#pWord').val('');
								$('#pWord2').val('');
								$('#uType').val('');
								$('#uStatus').val('');
							}
						}
					});
				}
				else
				{
					var title   = 'Error!',
					message = 'Password does not match',
					type    = 'error',
					options = {};

					toastr[type](message, title, options);
					$('#saveUser').prop('disabled',false)
				}
			}

		});

		$('#userList tbody').on( 'click', 'button', function (){
			var id = $(this).val();

			$.ajax({
				type:"GET",
				url: 'showUserRowData/'+id,
				headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
				success: function (data){
					$('#fullName').val(data.uData.name);
					$('#userName').val(data.uData.email);
					$('#userId').val(data.uData.id);
					$('#uType').val(data.uData.type).prop('selected',true);
					$('#uStatus').val(data.uData.status).prop('selected',true);
					$('#saveUser').hide().prop('disabled',false)
					$('#upUser').show();

				}
			});

		});


		$('#upUser').click(function(){
			$('#saveUser').prop('disabled',true)
			
			if(	 $('#fullName').val() == '' || $('#userName').val() == '' || $('#uType').val() == '' || $('#uStatus') == '' )
			{
				var title   = 'Error!',
				message = 'Please check user information',
				type    = 'error',
				options = {};

				toastr[type](message, title, options);
				$('#saveUser').prop('disabled',false)

			}
			else
			{
				if( $('#pWord').val() == $('#pWord2').val() )
				{
					var userInfo =
					{
						fullName: $('#fullName').val(),
						userId: $('#userId').val(),
						uName: $('#userName').val(),
						pWord: $('#pWord').val(),
						uType: $('#uType').val(),
						uStatus: $('#uStatus').val(),
						action: 2,
					}
					$.ajax({
						type:"POST",
						url: "{{route('users.store')}}",
						data: userInfo,
						headers: { 'X-CSRF-Token' : $('meta[name=csrf-token]').attr('content') },
						beforeSend: function() {
							$("#loadingState").show();
							$('#layout-main').fadeOut();
						},
						success: function (data){

							if(data=='error')
							{
								var title   = 'Error!',
								message = 'Username already exist!',
								type    = 'error',
								options = {};

								toastr[type](message, title, options);
								$("#loadingState").hide();
								$('#layout-main').fadeIn();
								$('#saveUser').prop('disabled',false)
							}
							else
							{
								var title   = 'User saved successfully!',
								message = 'User Data is ready!',
								type    = 'success',
								options = {};

								toastr[type](message, title, options);
								$('#userList').DataTable().ajax.reload();
								$("#loadingState").hide();
								$('#layout-main').fadeIn();
								$('#saveUser').prop('disabled',false);
								$('#fullName').val('');
								$('#userName').val('');
								$('#pWord').val('');
								$('#pWord2').val('');
								$('#uType').val('');
								$('#uStatus').val('');
								

							}
							
						}
					});
				}
				else
				{
					var title   = 'Error!',
					message = 'Password does not match',
					type    = 'error',
					options = {};

					toastr[type](message, title, options);
					$('#saveUser').prop('disabled',false)
				}
			}

		});

		$('#clearUser').click(function(){
			$('#saveUser').show();
			$('#fullName').val('');
			$('#userName').val('');
			$('#pWord').val('');
			$('#pWord2').val('');
			$('#uType').val('0');
			$('#uStatus').val('0');
			$('#upUser').hide();
		});

	});
</script>