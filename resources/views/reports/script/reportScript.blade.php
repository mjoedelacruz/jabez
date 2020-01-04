<script type="text/javascript">
	$(document).ready(function(){

		var d = new Date();

		var month = d.getMonth()+1;
		var day = d.getDate();
		var output = d.getFullYear()+'-'+ (month<10 ? '0' : '')+month+'-'+(day<10 ? '0' : '')+day;

		$('#dateStart').val(output);
		$('#dateEnd').val(output);
		$('#dateStartS').val(output);
		$('#dateEndS').val(output);
		$('#dateStartSD').val(output);
		$('#dateEndSD').val(output);
		$('#dateStartGoods').val(output);
		$('#dateEndGoods').val(output);
		$('#dateStartGDetails').val(output);
		$('#dateEndGDetails').val(output);
	    //datepickers
	    $('#dateStart').datepicker({
	    	format: 'yyyy-mm-dd',
	    });
	    $('#dateEnd').datepicker({
	    	format: 'yyyy-mm-dd',
	    });
	    $('#dateStartS').datepicker({
	    	format: 'yyyy-mm-dd',
	    });
	    $('#dateEndS').datepicker({
	    	format: 'yyyy-mm-dd',
	    });
	    $('#dateStartSD').datepicker({
	    	format: 'yyyy-mm-dd',
	    });
	    $('#dateEndSD').datepicker({
	    	format: 'yyyy-mm-dd',
	    });
	    $('#dateStartGoods').datepicker({
	    	format: 'yyyy-mm-dd',
	    });
	    $('#dateEndGoods').datepicker({
	    	format: 'yyyy-mm-dd',
	    });
	    $('#dateStartGDetails').datepicker({
	    	format: 'yyyy-mm-dd',
	    });
	    $('#dateEndGDetails').datepicker({
	    	format: 'yyyy-mm-dd',
	    });

	    //datepicker

		jQuery(document).ready(function() {
			$('#bpTypeReport').select2({ dir: 'ltr' });
			$('#reportContentGoods').select2({dir:'ltr'});
			$('#BpCodeReports').select2({dir:'ltr'});
			$('#BpCodeSReports').select2({dir:'ltr'});
			$('#BpCodeSDReports').select2({dir:'ltr'});
			$('#BpCodeGEReport').select2({dir:'ltr'});
		});

	});
</script>