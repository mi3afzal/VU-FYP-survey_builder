
<link href="<?=SITE_URL?>assets/css/jquery.formbuilder.css" rel="stylesheet" type="text/css">
<script src="<?=SITE_URL?>assets/js/jquery.formbuilder.js"></script>

<style type="text/css">
#survey_form{margin:20px 0px;}
#survey_form label{width:150px;font-weight:bold;display:inline-block;}
#survey_form input{}
</style>
<script type="text/javascript">
$(function(){
	$('#survey_builder').formbuilder({
		'save_url': '<?=SITE_URL?>?page=survey&cmd=save',
		//'load_url': 'example-load.php'
		'useJson' : true
	});
	$(function() {
		$("#survey_builder ul").sortable({ opacity: 0.6, cursor: 'move'});
	});
	$("#expiration_date").datepicker({
      	showButtonPanel: true,
		showOtherMonths: true,
      	selectOtherMonths: true,
		minDate: +1,
		dateFormat: 'yy-mm-dd',
    });
});
</script>
<div id="survey_form">
<label for="survey_title">Survey Title</label><input name="survey_title" id="survey_title" type="text" maxlength="99" style="width:300px;"><br />
<label for="expiration_date">Expiration Date</label><input name="expiration_date" id="expiration_date" type="text" value="<?=date('Y-m-d')?>"><br />
<label for="status">Status</label>
<select name="status" id="status">
	<?php
    $status_list = get_status_list(2);
	foreach($status_list as $status) echo '<option value="'.$status['id'].'">'.$status['title'].'</option>';
	?>
</select>
</div>
<div id="survey_builder"></div>