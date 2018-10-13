<h1>Registration</h1>
<form name="user_register" method="post" action="<?=SITE_URL?>?page=user&cmd=add">
<table width="800" cellspacing="0" cellpadding="0" id="content-table">
    <tr>
      	<td colspan="2" id="table-heading">Add new user</td>
    </tr>
    <tr>
    	<th width="25%">User Role</th>
    	<td><select name="role" id="role">
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
    	<td width="75%"><input type="text" name="first_name" id="first_name" value="<?=set_data_back('first_name')?>" /></td>
    </tr>
    <tr>
    	<th>Last Name</th>
    	<td><input type="text" name="last_name" id="last_name" value="<?=set_data_back('last_name')?>" /></td>
    </tr>
    <tr>
    	<th>Email Address</th>
    	<td><input type="text" name="email" id="email" value="<?=set_data_back('email')?>" /></td>
    </tr>
    <tr>
    	<th>Password</th>
    	<td><input type="password" name="password" id="password" minlength="6" value="<?=set_data_back('password')?>" /></td>
    </tr>
    <tr>
    	<th>Confirm Password</th>
    	<td><input type="password" name="confirm_password" id="confirm_password"  value="<?=set_data_back('confirm_password')?>" minlength="6" /></td>
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
    	<th colspan="2" style="text-align:center;"><input type="submit" name="register" value="Register" /></th>
    </tr>
</table>
</form>
<script type="text/javascript">
$(document).ready(function(){
	$('#role').val('<?=set_data_back('role')?>');
	$('#gender').val('<?=set_data_back('gender')?>');
});
</script>
