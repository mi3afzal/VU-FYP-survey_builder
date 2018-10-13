<link href="<?=SITE_URL?>assets/css/jquery.formbuilder.css" rel="stylesheet" type="text/css">
<script src="<?=SITE_URL?>assets/js/jquery.formbuilder.js"></script>
<style type="text/css">
ol{ list-style:none;padding:5px;}
</style>

<?php
require(SRV_ROOT.'config/form_builder.php');

if(!isset($_GET['sid']) or $_GET['sid'] == '') redirect(SITE_URL);
$survey_id = base64De($_GET['sid']);

$survey_query = "SELECT * FROM ".TBL_SURVEY." WHERE id = ".$survey_id;
$survey_ref = db_query($survey_query);
$survey_data = db_fetch_assoc($survey_ref);
$form_data = array('form_structure' => $survey_data['form_structure']);

echo '<h1>'.$survey_data['title'].'</h1>';
$form = new Formbuilder($form_data);
$form->render_html(SITE_URL.'?page=survey&cmd=responce&sid='.$_GET['sid']);

?>

