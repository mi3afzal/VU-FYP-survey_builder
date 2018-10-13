<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>::Survey Builder::</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="description" content="Survey Builder">
<meta name="keywords" content="Survey,Builder,VU Project">
<link rel="shortcut icon" href="images/logo.jpg"  />

<link href="<?=SITE_URL?>assets/css/style.css" rel="stylesheet" type="text/css">
<link href="<?=SITE_URL?>assets/css/jquery-ui.css" rel="stylesheet" type="text/css">
<link href="<?=SITE_URL?>assets/css/jMenu.jquery.css" rel="stylesheet" type="text/css" />

<script src="<?=SITE_URL?>assets/js/jquery.min.js" type="text/javascript"></script>
<script src="<?=SITE_URL?>assets/js/jquery-ui.min.js" type="text/javascript"></script>

<script type="text/javascript">
$(document).ready(function(){
	var URL = 'http://<?=$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']?>';
	$('#topmenu ul li a').filter(function(){
		var a_href = $(this).attr('href');
		if(URL == a_href){
			$('#topmenu a.selected').removeClass('selected');
			$(this).addClass('selected');
			return true;
		}
		return false;
	});
});
</script>

</head>
<body>

<div id="alert" style="display:none;width:100%;height:200px;position:fixed;bottom:0px;left:0px;overflow:auto;z-index:1000;background-color:#FFF;border:1px solid #00F;"></div>

<div id="header">
  	<div id="logo">Survey Builder</div>
 
  	<div id="topmenu">
		<ul id="jMenu">
			<li><a class="fNiv" href="<?=SITE_URL?>"><span>Home</span></a></li>
            <?php if(check_login() == FALSE){ ?>
			<li><a class="fNiv" href="<?=SITE_URL?>?page=registration"><span>Register</span></a></li>
			<li><a class="fNiv" href="<?=SITE_URL?>?page=login"><span>Login</span></a></li>
            <?php 
			}
			else{ 
				$user_type = $_SESSION['user_array']['level'];
				$query = "SELECT m.page, m.title 
				FROM ".TBL_MODULES." AS m, ".TBL_MODULES_TO_USER_TYPE." AS mu 
				WHERE m.id = mu.module_id AND mu.user_type_id = ".$user_type;
				$ref = db_query($query);
				while($row = db_fetch_array($ref))
				{
					if($row['page'] == 'permissions') continue;
					echo '<li><a class="fNiv" href="'.SITE_URL.'?page='.$row['page'].'"><span>'.$row['title'].'</span></a></li>';
				}
			?>
            	<li><a class="fNiv" href="<?=SITE_URL?>?page=edit_profile"><span>Profile</span></a></li>
				<li><a class="fNiv" href="<?=SITE_URL?>?page=user&cmd=logout"><span>Log Out</span></a></li>
            <?php } ?>
            <div class="clear"></div>
		 </ul>
  	</div>
</div>

<?php show_alert(); ?>
<div id="content">
