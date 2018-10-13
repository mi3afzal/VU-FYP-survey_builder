<?php
include_once(SRV_ROOT.'config/controller_function.php');

// not directly browseable and stop posting from any other url
// it is access able through URL having 'cmd_permition' but there is no post data
if(!isset($_SERVER['HTTP_REFERER']) or !preg_match("/".$_SERVER['HTTP_HOST']."/", $_SERVER['HTTP_REFERER']))
{
	if(isset($_GET['cmd_permition']) and $_GET['cmd_permition'] != '' and count($_POST) <= 0)
	{
		$permition_time = base64De($_GET['cmd_permition']);
		if($permition_time < time())
		{
			add_alert('Link has been expired.', 'R');
			redirect(SITE_URL);
		}	
	}
	else{
		log_error('Some one is trying to get in models');
		redirect(SITE_URL);
	}
}


// if there is no get or post array return back
if(count($_POST) <= 0 and count($_GET) <= 0)
{
	add_alert('You are trying to hack this site idiot! ');
	redirect('?page=home');
}
empty_page_data();


$_SESSION['page_post_data'] = input_secure($_POST);
$_GET = input_secure($_GET);
$_POST = '';


include($cur_page.'_sb.php');
$cmd = $_GET['cmd'];
if(function_exists($cmd)) $cmd($_SESSION['page_post_data']);
else log_error('Function dose not exist."'.$cmd.'"');

redirect($_SERVER['HTTP_REFERER']);
?>