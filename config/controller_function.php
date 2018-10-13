<?php

function form_validation($array)
{
	$return = true;
	foreach($array as $k => $v) 
	{
		//echo $k." => ".$v . "<br />";
		if(is_array($v))
		{
			foreach($v as $r => $d)
			{
				if($d == '')
				{
					$field_name = str_replace('_', ' ', $k);
					$field_name = ucwords($field_name);
					add_alert($field_name.' is required');
					$return = false;
				}
			}
		}
		else if($v == '')
		{
			$field_name = str_replace('_', ' ', $k);
			$field_name = ucwords($field_name);
			add_alert($field_name.' is required');
			$return = false;
		}
	}
	return $return;
}

function input_secure($input)
{
	if(is_array($input))
	{
		foreach($input as $k => $v)
		{
			$input[$k] = input_secure($v);
		}
		return $input;
	}
	else
	{
		$v = trim($input);
		$v = htmlspecialchars($v, ENT_QUOTES, "UTF-8");
		$v = mysql_real_escape_string($v);
		return $v;
	}
}



// Check upload file is an image
function check_image($type)
{
	if($type == "image/gif" || $type == "image/png" || $type == "image/jpeg" || $type == "image/jpg"){
		return TRUE;
	}
	else if($type == "image/x-png" || $type == "image/bmp" || $type == "image/pjpeg"){
		return TRUE;
	}
	else{
		return FALSE;
	}
}

//image
function uplopad_image($file, $folder = '')
{	
	$name = $file["name"];
	$return_fname = 'no_image.png';
	if(check_image($file['type']) == TRUE)
	{
		$return_fname = time()."_".substr($name, -20);
		move_uploaded_file($file["tmp_name"], SRV_ROOT.'assets/'.$folder.'/'.$return_fname);
	}
	return $return_fname;
}


function delete_image($name, $folder = '')
{
	if($name == '') return false;
	if(preg_match("/no_image.png/",$name)) return false;
	unlink(SRV_ROOT.'assets/'.$folder.'/'.$name);
	return true;
}




//Email Address validation code
function validemail($email) 
{
 	if (!preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email))return FALSE;
 	else return TRUE;
}
//PHONE NUMBER VALIDATION CODE
function validnumber($number)
{
	if(!preg_match("/[^0-9\ ]+$/",$number)) return TRUE;
	else return FALSE;
}

?>