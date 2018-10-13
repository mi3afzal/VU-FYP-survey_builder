<?PHP
if(preg_match("/cli_config.php/",$_SERVER['SCRIPT_FILENAME']))
{
	header("location:../index.php");
	exit;
}

if(!preg_match("/www/",$_SERVER['SERVER_NAME']) and !preg_match("/localhost/", $_SERVER['SERVER_NAME']))
{
	header("location: http://www.".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']);
	exit;
}


//define flags
define('DEBUG_MODE', TRUE); // should be false for live system
define('REDIRECTION', FALSE); // should be true for live system


// Define strings
define("SITE_NAME", "Survey Builder");
define("SITE_TITLE", "Survey Builder ");
define("TABLE_PREFIX", "sb_");
define("ADMIN_EMAIL", "bc080200974@vu.edu.pk"); //change this for live system
define("SITE_EMAIL", "bc080200974@vu.edu.pk"); // change this for live system info@cliquer.com.au


// Define data base config
if($_SERVER['HTTP_HOST'] == 'localhost')
{
	define("HOST_NAME", "localhost");
	define("DATA_BASE", "per_surveybuilder");
	define("USER_NAME", "root");
	define("PASSWORD", "");
}
else{
	define("HOST_NAME", '');
	define("DATA_BASE", '');
	define("USER_NAME", '');
	define("PASSWORD", '');
}

// Define data base table
define("TBL_MODULES", TABLE_PREFIX."modules");
define("TBL_MODULES_ACTIONS", TABLE_PREFIX."modules_actions");
define("TBL_MODULES_ACTIONS_TO_USER_TYPE", TABLE_PREFIX."modules_actions_to_user_type");
define("TBL_MODULES_TO_USER_TYPE", TABLE_PREFIX."modules_to_user_type");

define("TBL_STATUS_LIST", TABLE_PREFIX."status_list");
define("TBL_SURVEY", TABLE_PREFIX."survey");
define("TBL_SURVEY_RESPONSES", TABLE_PREFIX."survey_responses");
define("TBL_USERS", TABLE_PREFIX."users");
define("TBL_USER_TYPES", TABLE_PREFIX."user_types");

// geting site urls. do not change it
$thisFile = str_replace('\\', '/', __FILE__); //D:/xampp/htdocs/sun/cliquere/config/cli_config.php
$docRoot = $_SERVER['DOCUMENT_ROOT'];	//D:/xampp/htdocs
$webRoot  = str_replace(array($docRoot, 'config/config.php'), '', $thisFile); // 	/sun/cliquer/
$srvRoot  = str_replace('config/config.php', '', $thisFile);	//D:/xampp/htdocs/sun/cliquer/

$http = 'http://';
if(isset($_SERVER["HTTPS"]) and $_SERVER["HTTPS"] == "on") $http = 'https://';
$site_url = $http.$_SERVER['SERVER_NAME']; //http://localhost

// Define site urls
define('SRV_ROOT', $srvRoot); //	D:/xampp/htdocs/sun/cliquer/
define("SITE_URL", $site_url.$webRoot); //	http://localhost/sun/cliquer/

/*********************** END CONFIG ********************************/
if(DEBUG_MODE){
	error_reporting(988794);
}
else{
	error_reporting(0);
}

function get_page_name($page_link)
{
	$page_name = basename($page_link);
	$position = strpos($page_name, '?');
	if($position > 0) $page_name = substr($page_name, 0, $position);
	return $page_name;
}

$ref_page = '';
$cur_page = 'home';
if(isset($_GET['page']) and $_GET['page'] != '') $cur_page = $_GET['page'];
if(isset($_SERVER['HTTP_REFERER'])) $ref_page = get_page_name($_SERVER['HTTP_REFERER']);


include_once('function.php');
include_once('database.php');
include_once('session.php');
?>