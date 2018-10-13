<?php
$role_disable = 'disabled="disabled"';
$user_id = $_SESSION['user_array']['user_id'];
if(isset($_GET['uid']) and $_GET['uid']!='')
{
	$user_id = base64De($_GET['uid']);
	$role_disable = '';
}
$user_query = "SELECT * FROM ".TBL_USERS." WHERE id = ".$user_id;
$user_ref = db_query($user_query);
$user_row = db_fetch_array($user_ref);
?>
<h1>Profile</h1>
<form name="user_register" method="post" action="<?=SITE_URL?>?page=user&cmd=update&uid=<?=base64En($user_id)?>">
<table width="800" cellspacing="0" cellpadding="0" id="content-table">
    <tr>
      	<td colspan="2" id="table-heading">Add new user</td>
    </tr>
    <tr>
    	<th width="25%">User Role</th>
    	<td width="75%"><select name="role" id="role" <?=$role_disable?>>
        <option value="">Select Role</option>
        <?php
        $query = "SELECT * FROM ".TBL_USER_TYPES." WHERE status_list_id = 1 ";
		$ref = db_query($query);
		while($row = db_fetch_array($ref))
		{
			echo '<option value="'.$row['id'].'">'.$row['type'].'</option>';
		}
		?>
      	</select></td>
    </tr>
    <tr>
    	<th width="25%">First Name</th>
    	<td width="75%"><input type="text" name="first_name" id="first_name" value="<?=$user_row['first_name']?>" /></td>
    </tr>
    <tr>
    	<th>Last Name</th>
    	<td><input type="text" name="last_name" id="last_name" value="<?=$user_row['last_name']?>" /></td>
    </tr>
    <tr>
    	<th>Email Address</th>
    	<td><input type="text" name="email" id="email" value="<?=$user_row['email']?>" disabled="disabled" /></td>
    </tr>
    <tr>
    	<th>Gender</th>
    	<td><select name="gender" id="gender">
        <option value="">Select</option>
        <option value="Male">Male</option>
        <option value="Female">Female</option>
        <option value="Others">Others</option>
      	</select></td>
    </tr>
    <tr>
    	<th colspan="2" style="text-align:center;"><input type="submit" name="update_profile" id="update_profile" value="Update Profile" /></th>
    </tr>
</table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('#role').val('<?=$user_row['user_types_id']?>');
	$('#gender').val('<?=$user_row['gender']?>');
});
</script>

<div style="height:30px;"></div>
<form name="change_password" method="post" action="<?=SITE_URL?>?page=user&cmd=update_password&uid=<?=base64En($user_id)?>">
<table width="800" border="0" cellpadding="0" cellspacing="0" id="content-table">
    <tr>
      	<td colspan="2" id="table-heading">Change Password</td>
    </tr>
    <tr>
    	<th width="25%">Old Password</th>
    	<td width="75%"><input type="password" name="old_password" id="old_password" minlength="6" /></td>
    </tr>
    <tr>
    	<th>New Password</th>
    	<td><input type="password" name="password" id="password" minlength="6" /></td>
    </tr>
    <tr>
    	<th>Confirm Password</th>
    	<td><input type="password" name="confirm_password" id="confirm_password" minlength="6" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="center"><input name="update_password" id="update_password" value="Update Password" type="submit" /></td>
    </tr>
</table>
</form>
