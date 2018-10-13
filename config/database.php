<?php
if(preg_match("/database.php/", $_SERVER['SCRIPT_FILENAME']))
{
	header("location:../index.php");
	exit;
}

$dbConn = mysql_connect(HOST_NAME, USER_NAME, PASSWORD); //or die ('MySQL connect failed. ' . mysql_error());
$db = mysql_select_db(DATA_BASE); //or die('Cannot select database. ' . mysql_error());

//mysql_close($con);


function db_query($sql)
{
	$result = mysql_query($sql) or log_error(mysql_error());
	return $result;
}

function db_affected_rows()
{
	return mysql_affected_rows();
}

function db_fetch_array($result, $resultType = MYSQL_ASSOC) {
	return mysql_fetch_array($result, $resultType);
}

function db_fetch_assoc($result)
{
	return mysql_fetch_assoc($result);
}

function db_fetch_row($result) 
{
	return mysql_fetch_row($result);
}

function db_free_result($result)
{
	return mysql_free_result($result);
}

function db_num_rows($result)
{
	return mysql_num_rows($result);
}

function db_select_db($dbName)
{
	return mysql_select_db($dbName);
}

function db_insert_id()
{
	return mysql_insert_id();
}
?>