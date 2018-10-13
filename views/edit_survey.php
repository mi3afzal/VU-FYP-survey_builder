<?php
if(!isset($_GET['sid']) or $_GET['sid'] == '') return;
$survey_id = base64De($_GET['sid']);

$get_survey_q = "SELECT * FROM ".TBL_SURVEY." WHERE id = ".$survey_id;
$get_survey_r = db_query($get_survey_q);
$survey = db_fetch_array($get_survey_r);
?>

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
		'load_url': '<?=SITE_URL?>?page=survey&cmd=load&sid=<?=$_GET['sid']?>',
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
<a href="<?=SITE_URL?>?page=build_new_survey" class="button">Build New Survey</a>
<div id="survey_form">
<label for="survey_title">Survey Title</label>
<input name="survey_title" id="survey_title" type="text" maxlength="99" value="<?=$survey['title']?>" style="width:300px;"><br />
<label for="expiration_date">Expiration Date</label>
<input name="expiration_date" id="expiration_date" type="text" value="<?=$survey['expired_at']?>"><br />
<label for="status">Status</label>
<select name="status" id="status">
	<?php
    $status_list = get_status_list(2);
	foreach($status_list as $status){ 
		$selected = '';
		if($status['id'] == $survey['status']) $selected = 'selected="selected"';
		echo '<option value="'.$status['id'].'" '.$selected.'>'.$status['title'].'</option>';
	}
	?>
</select>
</div>
<div id="survey_builder"></div>