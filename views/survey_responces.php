<style type="text/css">
.response{padding:5px;border:1px solid #D0D0D0;margin:15px 0px 0px 0px;}
.response p{line-height:20px;padding:10px;display:block;}
.response span{padding:5px 10px;margin:5px;line-height:20px;border:1px solid #D0D0D0;box-shadow:0px 0px 3px #CCC;color:#999;}
</style>

<?php
function elemId($label, $prepend = false){
	if(is_string($label)){
		$prepend = is_string($prepend) ? $this->elemId($prepend).'-' : false;
		return $prepend.strtolower( preg_replace("/[^A-Za-z0-9_]/", "", str_replace(" ", "_", $label) ) );
	}
	return false;
}

if(!isset($_GET['sid']) or $_GET['sid'] == '') return;
$survey_id = base64De($_GET['sid']);

$get_survey_q = "SELECT * FROM ".TBL_SURVEY." WHERE id = ".$survey_id;
$get_survey_r = db_query($get_survey_q);
$survey = db_fetch_array($get_survey_r);
echo '<h1>'.$survey['title'].'</h1>';
	
$get_response_q = "SELECT * FROM ".TBL_SURVEY_RESPONSES." WHERE survey_id = ".$survey_id;
$get_response_r = db_query($get_response_q);
if(db_num_rows($get_response_r) <= 0) echo '<h3>No one has conduct this survey yet</h3>';
while($response = db_fetch_array($get_response_r))
{
	$responder_name = 'Anonymous';
	if($response['user_id'] > 0)
	{
		$user_query = "SELECT * FROM ".TBL_USERS." WHERE id = ".$response['user_id'];
		$user_ref = db_query($user_query);
		$user_row = db_fetch_array($user_ref);
		$responder_name = $user_row['first_name'].' '.$user_row['last_name'];
	}
	
	$survey_array = json_decode($survey['form_structure'], true);
	$response_array = json_decode($response['response'], true);
	/*echo '<pre>';
	print_r($survey_array);
	print_r($response_array);
	echo '</pre>';*/
	echo '<div class="response"><p>';
	foreach($survey_array as $index => $element)
	{
		if($element['cssClass']=='input_text' or $element['cssClass']=='textarea')
		{
			echo $element['values'].' = '.$response_array[elemId($element['values'])].'<br />';
		}
		else if($element['cssClass']=='radio' or $element['cssClass']=='select')
		{
			echo $element['title'].' = '.$response_array[elemId($element['title'])].'<br />';
		}
		else if($element['cssClass']=='checkbox')
		{
			echo $element['title'].' = ';
			$checked_values_array =  array();
			foreach($element['values'] as $value) 
			{
				$res_array_index = elemId($value['value']);
				if($value['value']==$response_array[$res_array_index]) $checked_values_array[] = $response_array[$res_array_index];
			}
			echo implode(', ', $checked_values_array).'<br />';
		}
	}
	echo '</p>
	<span>'.$responder_name.'</span>
	<span>'.$response['created_at'].'</span>
	</div>';
}
?>