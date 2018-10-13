<?php 
if(isset($_GET['email']) and $_GET['email'] != '')
{ 
?>
<h1>New Password</h1>
<form name="change_password" method="post" action="<?=SITE_URL?>?page=user&cmd=save_new_password&email=<?=$_GET['email']?>">
<table width="400" border="0" cellpadding="0" cellspacing="0" id="content-table">
    <tr>
      	<td colspan="2" id="table-heading">Make a new Password</td>
    </tr>
    <tr>
    	<th width="25%">Password</th>
    	<td width="75%"><input type="password" name="password" id="password" minlength="6" /></td>
    </tr>
    <tr>
    	<th>Confirm Password</th>
    	<td><input type="password" name="confirm_password" id="confirm_password" minlength="6" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="center"><input name="submit" id="submit" value="Change Password" type="submit" /></td>
    </tr>
</table>
</form>

<?php } else { ?>

<h1>Forgot Password</h1>
<form name="forgot_password" method="post" action="<?=SITE_URL?>?page=user&cmd=forgot_password">
<table width="400" border="0" cellpadding="0" cellspacing="0" id="content-table">
    <tr>
      	<td colspan="2" id="table-heading">Get new Password</td>
    </tr>
    <tr>
    	<th width="25%">Email</th>
    	<td width="75%"><input name="email" id="email" type="text" /></td>
    </tr>
    <tr>
    	<td colspan="2" align="center"><input name="submit" id="submit" value="Submit" type="submit" /></td>
    </tr>
</table>
</form>
<?php } ?>