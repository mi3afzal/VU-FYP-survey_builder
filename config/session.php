<?php
if(preg_match("/session.php/",$_SERVER['SCRIPT_FILENAME']))
{
	header("location:../index.php");
	exit;
}

//starting session 
session_start();

/*****************************************
** when user first time browse the site 
** register all session variables 
*****************************************/
if(!isset($_SESSION['page_name']))$_SESSION['page_name'] = '';
if(!isset($_SESSION['page_errors']))$_SESSION['page_errors'] = '';
if(!isset($_SESSION['page_error_type']))$_SESSION['page_error_type'] = '';
if(!isset($_SESSION['page_post_data']))$_SESSION['page_post_data'] = '';
if(!isset($_SESSION['page_query']))$_SESSION['page_query'] = '';

if(!isset($_SESSION['site_array'])) $_SESSION['site_array'] = '';
if(!isset($_SESSION['site_errors'])) $_SESSION['site_errors'] = '';
if(!isset($_SESSION['user_array'])) $_SESSION['user_array'] = '';

/*********************************************************************
** after first time and so on
** page_errors, page_error_type, page_post_data and
** page_query will hold data for a page
** if page name change all the data will erase.
** $_SESSION['page_name'] = $cur_page; is located in header.php
**********************************************************************/
function empty_page_data()
{
	if(check_alert() == true) return;
	$_SESSION['page_post_data'] = '';
	$_SESSION['page_query'] = '';
}
if($_SESSION['page_name'] != $cur_page and $cur_page != 'signin') 
{
	empty_page_data();
}
$_SESSION['page_name'] = $cur_page;


/*********************************************************************
**	check_login() > check user is login or not
**	check_admin_login() > check admin is login 
**	check_authentication() > check user is login to view secure pages
**********************************************************************/
function check_login()
{
	if(!empty($_SESSION['user_array']['user_id']) and 
	   $_SESSION['user_array']['code'] == 'auG45*@#{po}' and 
	   $_SESSION['user_array']['level'] != '' and 
	   $_SESSION['user_array']['login'] == true
	) return true;
	else return false;
}

?>