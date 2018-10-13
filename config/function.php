<?php
if(preg_match("/function.php/",$_SERVER['SCRIPT_FILENAME']))
{
	header("location:../index.php");
	exit;
}

function log_error($error)
{
	$_SESSION['site_errors'][] = date('d-m-Y H:i', time()).' => '.$error;
}

function check_alert()
{
	if(is_array($_SESSION['page_errors']) and count($_SESSION['page_errors']) > 0 and $_SESSION['page_error_type'] == 'R') return true;
	return false;
}

function show_alert()
{
	// check is there any data for display or return back
	if(!is_array($_SESSION['page_errors']) or count($_SESSION['page_errors']) <= 0) return;
	switch($_SESSION['page_error_type'])
	{
		case 'Y': //yellow for restrictions
			$colour = '#000';
			$bgcolour = '#fff7cd';
			break;
		case 'G': // green for success
			$colour = '#FFF';
			$bgcolour = '#090';
			break;
		case 'LB': //lite blue
			$colour = '#000';
			$bgcolour = '#eaf5f9';
			break;
		case 'R': //red for fail
		default: //red for fail
			$colour = 'RED';
			$bgcolour = '#FFF';
			break;
	}
	
	echo '<div class="alert" style="color:'.$colour.';background-color:'.$bgcolour.';">';
	foreach($_SESSION['page_errors'] as $v)
	{
		echo '&#8226; '.$v.'<br />';
	}
	echo '</div>';
	
	$_SESSION['page_errors'] = '';
	$_SESSION['page_error_type'] = '';
}

function add_alert($alert_text, $alert_type = 'R')
{
	$_SESSION['page_errors'][] = $alert_text;
	$_SESSION['page_error_type'] = $alert_type;
}

function set_data_back($field_name)
{
	if(isset($_SESSION['page_post_data'][$field_name]))
	{
		return $_SESSION['page_post_data'][$field_name];
	}
}

function redirect($page) 
{
	if(!headers_sent())
	{
		header("location:$page");
		exit;
	}
	else
	{
		echo "<script>window.location.href='$page'</script>";
		exit;
	}
}


function base64En($val, $num=3) 
{
	for($i=0; $i<$num; $i++) {
		$val = base64_encode($val);
	}
	return $val;
}

function base64De($val, $num=3) 
{
	for($i=0; $i<$num; $i++) {
		$val = base64_decode($val);
	}
	return $val;
}


/* custom function */
function get_status_list($limit=0)
{
	$limit_string = '';
	if($limit > 0) $limit_string = ' LIMIT 0, '.$limit;
	$query = "SELECT * FROM ".TBL_STATUS_LIST.$limit_string;
	$ref = db_query($query);
	
	$list = array();
	while($row = db_fetch_array($ref))
	{
		$list[] = $row;
	}
	return $list;
}

function get_permission($page, $alerts = false)
{
	if($page == '') return true;
	$query = "SELECT * FROM ".TBL_MODULES_ACTIONS." WHERE action = '$page' ";
	$ref = db_query($query);
	if(db_num_rows($ref) == 1)
	{
		if(check_login() == false){
			if($alerts) add_alert('You have to login to view this page', 'Y');
			return false;
		}
		if($page == 'edit_profile' and !isset($_GET['uid'])) return true;
		$module_action = db_fetch_array($ref);
		$user_type = $_SESSION['user_array']['level'];
		$query = "SELECT * FROM ".TBL_MODULES_ACTIONS_TO_USER_TYPE." WHERE module_action_id=".$module_action['id']." AND user_type_id=".$user_type;
		$ref = db_query($query);
		if(db_num_rows($ref) > 0) return true;
		else{
			if($alerts) add_alert("You don't have permission to access this page ", 'Y');
			return false;
		}
	}
	else return true;
	
}
?>