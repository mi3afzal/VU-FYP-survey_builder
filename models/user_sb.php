<?php

function _send_forgot_password_email($email, $confirm_url)
{
	$to = $email;
	$subject = "New password request at".SITE_TITLE;
	$message = 'You have requested a new password.<br />
	Please click the link below to make a new password.<br /><br />
	<a href="'.$confirm_url.'">'.$confirm_url.'</a><br /><br />
	This link is valid for 48 hours.';
	$headers = "MIME-Version: 1.0"."\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1"."\r\n";
	$headers .= 'From: <'.SITE_EMAIL.'>'."\r\n";
	
	include_once(SRV_ROOT.'config/mail-lib/mailer.inc.php');
	$send_mail = Mailer(SITE_NAME, SITE_EMAIL, $to, "mail.sunztech.com", "info@sunztech.com", "msnyahoo786", $subject, $message, true);
	if($send_mail) add_alert('New password link has been send on your email address. Please follow the steps given in email.', 'G');
	else add_alert('Unable to send email with new password link, Please contact to the site admin.', 'R');
}

function _send_signup_email($email, $confirm_url)
{
	$to = $email;
	$subject = "New Member Registration";
	$message = 'Thanks for your registration.<br />
	Please click the link below to confirm your registration.<br /><br />
	<a href="'.$confirm_url.'">'.$confirm_url.'</a><br /><br />
	This link is valid for 48 hours.';
	$headers = "MIME-Version: 1.0"."\r\n";
	$headers .= "Content-type:text/html;charset=iso-8859-1"."\r\n";
	$headers .= 'From: <'.SITE_EMAIL.'>'."\r\n";

	include_once(SRV_ROOT.'config/mail-lib/mailer.inc.php');
	$send_mail = Mailer(SITE_NAME, SITE_EMAIL, $to, "mail.sunztech.com", "info@sunztech.com", "msnyahoo786", $subject, $message, true);
	if($send_mail) add_alert('An activation link has been send on your email address. Please follow the steps given in email.', 'G');
	else add_alert('Unable to send email with activation link, Please contact to the site admin.', 'R');
}

function resend_activation_link()
{
	$email = base64De($_GET['email']);
	$query = "SELECT * FROM ".TBL_USERS." WHERE email = '$email' ";
	$ref = db_query($query);
	if(db_num_rows($ref) != 1) add_alert('Email address dose not exists');
	else{
		$user_row = db_fetch_array($ref);
		
		$vc = md5(time().$email);
		$permition_time = base64En(time()+(2*24*60*60)); // 2 days
		$confirm_url = SITE_URL.'?page=user&cmd=activate_account&cmd_permition='.$permition_time.'&pass_key='.$vc;
		log_error($confirm_url);
	
		$query = "UPDATE ".TBL_USERS." SET verification_code='$vc', status_list_id=6 WHERE id=".$user_row['id'];
		if(!db_query($query)){
			add_alert('Unable to process your request, Please try later.', 'R');
		}
		else{
			empty_page_data();
			add_alert('Your new activation link request has been processed.', 'G');
			_send_signup_email($email, $confirm_url);
		}
	}
	redirect(SITE_URL.'?page=login');
}

function resend_new_password_link()
{
	$email = base64De($_GET['email']);
	$query = "SELECT * FROM ".TBL_USERS." WHERE email = '$email' ";
	$ref = db_query($query);
	if(db_num_rows($ref) != 1) add_alert('Email address dose not exists');
	else{
		$user_row = db_fetch_array($ref);
		
		$vc = md5(time().$email);
		$permition_time = base64En(time()+(2*24*60*60)); // 2 days
		$confirm_url = SITE_URL.'?page=user&cmd=make_new_password&cmd_permition='.$permition_time.'&pass_key='.$vc;
		log_error($confirm_url);
	
		$query = "UPDATE ".TBL_USERS." SET verification_code='$vc', status_list_id=7 WHERE id=".$user_row['id'];
		if(!db_query($query)){
			add_alert('Unable to process your request, Please try later.', 'R');
		}
		else{
			empty_page_data();
			add_alert('Your new password link request has been processed.', 'G');
			_send_forgot_password_email($email, $confirm_url);
		}
	}
	redirect(SITE_URL.'?page=login');
}

// forgot password step 1 => user will submit email address
// this function will email new password link
function forgot_password($post_array)
{
	if(form_validation($post_array) == false) return;
	extract($post_array);
	if(!validemail($email) ) add_alert('Please enter correct email type'); 
	if(check_alert() == true) return;
	
	$query = "SELECT * FROM ".TBL_USERS." WHERE email = '$email' ";
	$ref = db_query($query);
	if(db_num_rows($ref) != 1) add_alert('Email address dose not exists');
	$user_row = db_fetch_array($ref);
	if($user_row['status_list_id']==2) add_alert('Your account has beed disabled by admin.');
	else if($user_row['status_list_id']==3) add_alert('You account has been marked as deleted.');
	else if($user_row['status_list_id']==4) add_alert('You account has been banned.');
	else if($user_row['status_list_id']==6){
		$permition_time = base64En(time()+(2*60*60)); //2 hour
		$resend_activation_link = SITE_URL.'?page=user&cmd=resend_activation_link&cmd_permition='.$permition_time.'&email='.base64En($email);
		add_alert('Your account is not activated yet. <a href="'.$resend_activation_link.'">resend activation link</a>');
	}
	else if($user_row['status_list_id']==7){ 
		$permition_time = base64En(time()+(2*60*60)); //2 hour
		$resend_new_password_link = SITE_URL.'?page=user&cmd=resend_new_password_link&cmd_permition='.$permition_time.'&email='.base64En($email);
		add_alert('You already have requested for new password. <a href="'.$resend_new_password_link.'">resend new password link</a>');
	}
	else if($user_row['status_list_id']==1){
		$vc = md5(time().$email);
		$permition_time = base64En(time()+(2*24*60*60)); // 2 days
		$confirm_url = SITE_URL.'?page=user&cmd=make_new_password&cmd_permition='.$permition_time.'&pass_key='.$vc;
		log_error($confirm_url);

		$query = "UPDATE ".TBL_USERS." SET verification_code='$vc', status_list_id=7 WHERE id=".$user_row['id'];
		if(!db_query($query)){
			add_alert('Unable to process your request, Please try later.', 'R');
		}
		else{
			empty_page_data();
			add_alert('Your new password request has been processed.', 'G');
			_send_forgot_password_email($email, $confirm_url);
		}
	}
}

// forgot password step 2 => from email link user will come here
// this function will make sure user is right then ask user to add new password
function make_new_password()
{
	$pass_key = $_GET['pass_key'];
	$sql = "SELECT * FROM ".TBL_USERS." WHERE verification_code = '$pass_key' AND status_list_id = 7";
	$result = db_query($sql);
	
	if(db_num_rows($result) != 1) add_alert('Confirmation code is not correct.', 'R');
	else{
		$row = db_fetch_array($result);
		$_SESSION['user_array']['user_id'] = $row['id'];
		redirect(SITE_URL.'?page=forgot_password&email='.base64En($row['email']));
	} 
	redirect(SITE_URL);
}

// forgot password step 3 => users will submit new password
// this function will save new password
function save_new_password($post_array)
{
	if(form_validation($post_array) == false) return;
	extract($post_array);
	
	if(strlen($password) < 6) add_alert('Password must be of 6 chracters atleast.');
	if($password != $confirm_password) add_alert('Password and confirm password dose not match.');
	if(check_alert() == true) return;
	
	$email = base64De($_GET['email']);
	$sql = "SELECT * FROM ".TBL_USERS." WHERE email = '$email'";
	$result = db_query($sql);
	
	if(db_num_rows($result) != 1) add_alert('Submition link is not correct.');
	else{
		$row = db_fetch_array($result);
		if($row['id'] != $_SESSION['user_array']['user_id']) add_alert('URL has been expired.');
		else if($row['status_list_id'] != 7) add_alert('You already have saved new password.');
		else{
			$pass = md5($password);
			db_query("UPDATE ".TBL_USERS." SET status_list_id=1, verification_code='', password='$pass' WHERE id = ".$row['id']);
			if($pass == $row['password']) add_alert('Your new password is same as old one.', 'Y');
			else add_alert('New password has been saved.', 'G');
		} 
	}

}


function add($post_array)
{
	if(form_validation($post_array) == false) return;
	extract($post_array);
	
	$sqls = "SELECT * FROM ".TBL_USERS." WHERE email = '$email' ";
	$results = db_query($sqls);
	if(db_num_rows($results) > 0) add_alert('Your given email id already exists');
	if(!validemail($email) ) add_alert('Please enter correct email type.'); 
	if(strlen($password) < 6) add_alert('Password must be of 6 chracters atleast.');
	if($password != $confirm_password) add_alert('Password and confirm password dose not match.');
	if(check_alert() == true) return;
	
	$pass = md5($password);
	$vc = md5(time().$email);
	$permition_time = base64En(time()+(2*24*60*60));
	$confirm_url = SITE_URL."?page=user&cmd=activate_account&cmd_permition=".$permition_time."&pass_key=".$vc;
	log_error($confirm_url);
	
	$user_type = 3;
	if(isset($role) and $role!='' and check_login()) $user_type = $role;
	
	$insrt = "INSERT INTO ".TBL_USERS." (first_name, last_name, email, password, gender, verification_code, user_types_id, status_list_id, created_at) VALUES ('$first_name', '$last_name', '$email', '$pass', '$gender', '$vc', $user_type, 6, NOW())";

	if(!db_query($insrt))
	{
		add_alert('Unable to register, Please try later.', 'R');
	}
	else{
		empty_page_data();
		add_alert('Your registration has been completed.', 'G');
		_send_signup_email($email, $confirm_url);		
	}
}

function activate_account()
{
	$pass_key = $_GET['pass_key'];
	$sql = "SELECT * FROM ".TBL_USERS." WHERE verification_code = '$pass_key' AND status_list_id = 6";
	$result = db_query($sql);
	
	if(db_num_rows($result) != 1) add_alert('Confirmation code is not correct.', 'R');
	else{
		$row = db_fetch_array($result);
		db_query("UPDATE ".TBL_USERS." SET status_list_id = 1, verification_code = '' WHERE id = ".$row['id']);
		add_alert('Your e-mail address has been Verified.', 'G');
	} 
	redirect(SITE_URL.'?page=login');
}

function update_password($post_array)
{
	if(form_validation($post_array) == false) return;
	extract($post_array);
	
	$user_id = base64De($_GET['uid']);
	$user_query = "SELECT * FROM ".TBL_USERS." WHERE id = ".$user_id;
	$user_ref = db_query($user_query);
	$user_row = db_fetch_array($user_ref);
	
	if(md5($old_password) != $user_row['password']) add_alert('Old password is not correct.');
	if(strlen($password) < 6) add_alert('Password must be of 6 chracters atleast.');
	if($password != $confirm_password) add_alert('Password and confirm password dose not match.');
	if(check_alert() == true) return;
	
	$pass = md5($password);
	db_query("UPDATE ".TBL_USERS." SET password = '$pass' WHERE id = ".$user_id);
	add_alert('Password has been updated.', 'G');
}

function update($post_array)
{
	if(form_validation($post_array) == false) return;
	extract($post_array);
	
	$user_id = base64De($_GET['uid']);
	$role_update = '';
	if(isset($role) and $role != '') $role_update = " , user_types_id = '".$role."' ";
	db_query("UPDATE ".TBL_USERS." SET first_name = '$first_name', last_name = '$last_name', gender = '$gender' $role_update WHERE id = ".$user_id);
	add_alert('Profile has been updated.', 'G');
}

function login($post_array)
{
	if(!isset($_SESSION['user_array']['login_attampts'])) $_SESSION['user_array']['login_attampts'] = 1;
	else $_SESSION['user_array']['login_attampts'] = $_SESSION['user_array']['login_attampts']+1;
	if($_SESSION['user_array']['login_attampts'] == 4) $_SESSION['user_array']['login_time'] = time();
	
	if(isset($_SESSION['user_array']['login_time']) and time() > ($_SESSION['user_array']['login_time'] + (15*60)))
	{ 
		$_SESSION['user_array']['login_time'] = 0;
		$_SESSION['user_array']['login_attampts'] = 1;
	}
	else if($_SESSION['user_array']['login_attampts'] > 3)
	{
		$remaing_time = date('i', ($_SESSION['user_array']['login_time'] + (15*60)) - time());
		add_alert('You are restricted to login for '.$remaing_time.' minutes due to number of failed login attempts.', 'R');
		return;
	}

	if(form_validation($post_array) == false) return;
	extract($post_array);
	$pass = md5($password);
		
	$checkuser = "SELECT * FROM ".TBL_USERS." WHERE email = '$email'";
	$referuser = db_query($checkuser);
	$user_data = db_fetch_array($referuser);
	
	if(db_num_rows($referuser) != 1) add_alert('Email address is not correct.', 'R');
	else if($user_data['status_list_id']==2) add_alert('Your account has beed disabled by admin.');
	else if($user_data['status_list_id']==3) add_alert('You account has been marked as deleted.');
	else if($user_data['status_list_id']==4) add_alert('You account has been banned.');
	else if($user_data['status_list_id']==6){
		$permition_time = base64En(time()+(2*60*60)); //2 hour
		$resend_activation_link = SITE_URL.'?page=user&cmd=resend_activation_link&cmd_permition='.$permition_time.'&email='.base64En($email);
		add_alert('Your account is not activated yet. <a href="'.$resend_activation_link.'">resend activation link</a>');
	}
	else if($user_data['status_list_id']==7){ 
		$permition_time = base64En(time()+(2*60*60)); //2 hour
		$resend_new_password_link = SITE_URL.'?page=user&cmd=resend_new_password_link&cmd_permition='.$permition_time.'&email='.base64En($email);
		add_alert('You have requested for new password. <a href="'.$resend_new_password_link.'">resend new password link</a>');
	}
	else if($user_data['password'] != $pass) add_alert('Password is not correct.', 'R');
	else if($user_data['status_list_id'] == 1) {
		empty_page_data();
		$_SESSION['user_array']['user_id'] = $user_data['id'];
		$_SESSION['user_array']['code'] = 'auG45*@#{po}';
		$_SESSION['user_array']['level'] = $user_data['user_types_id'];
		$_SESSION['user_array']['email'] = $user_data['email'];
		$_SESSION['user_array']['login'] = true;
		
		$_SESSION['user_array']['login_time'] = 0;
		$_SESSION['user_array']['login_attampts'] = 0;
		redirect(SITE_URL);
	}
}

function logout()
{
	$_SESSION['user_array']['user_id'] = '';
	$_SESSION['user_array']['code'] = '';
	$_SESSION['user_array']['level'] = '';
	$_SESSION['user_array']['email'] = '';
	$_SESSION['user_array']['login'] = false;
	redirect(SITE_URL);
}

function ajax_list()
{
	if(check_login() == false) exit;
	$dbColumns = array('id', 'first_name', 'last_name', 'email', 'gender', 'created_at');
	$sTable = TBL_USERS;
	$sIndexColumn = "id";
		
	//Paging
	$sLimit = "";
	if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
	{
		$sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];
	}
	
	//Ordering
	$sOrder = '';
	if(isset($_GET['iSortCol_0']))
	{
		$sOrder = "ORDER BY  ";
		for($i=0; $i<intval($_GET['iSortingCols']); $i++)
		{
			if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
				$sOrder .= $dbColumns[intval($_GET['iSortCol_'.$i])]." ".$_GET['sSortDir_'.$i].", ";
			}
		}
		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY") $sOrder = "";
	}
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = "";
	if(isset($_GET['sSearch']) && $_GET['sSearch'] != "")
	{
		if($sWhere == "") $sWhere = "WHERE (";
		else $sWhere .= " AND ";
		for($i=0; $i<count($aColumns); $i++)
		{
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".$_GET['sSearch']."%' OR ";
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	if(!isset($aColumns) or $aColumns == '') $aColumns = array();
	for($i=0; $i<count($aColumns); $i++)
	{
		if(isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '')
		{
			if($sWhere == "") $sWhere = "WHERE ";
			else $sWhere .= " AND ";
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".$_GET['sSearch_'.$i]."%' ";
		}
	}

	
	//SQL queries Get data to display
	$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $dbColumns))."
		FROM $sTable
		$sWhere
		$sOrder
		$sLimit";
	$rResult = db_query($sQuery);
	
	/* Data set length after filtering */
	$sQuery = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = db_query($sQuery);
	$aResultFilterTotal = db_fetch_array($rResultFilterTotal, MYSQL_NUM);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "SELECT COUNT(".$sIndexColumn.") FROM $sTable ";
	$rResultTotal = db_query( $sQuery);
	$aResultTotal = db_fetch_array($rResultTotal, MYSQL_NUM);
	$iTotal = $aResultTotal[0];
	
	//Output
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while($aRow = db_fetch_array( $rResult ))
	{
		$row = array();
		for($i=0; $i<count($dbColumns); $i++)
		{
			if($dbColumns[$i] == 'created_at') $row[] = date("d-m-Y", strtotime($aRow[$dbColumns[$i]]) );
			else $row[] = $aRow[$dbColumns[$i]];
		}
		$row[] = '<a href="'.SITE_URL.'?page=edit_profile&uid='.base64En($aRow['id']).'">Edit</a>';
		$output['aaData'][] = $row;
	}

	$json_output = json_encode($output);
	echo $json_output;
	exit;
}
?>