
<h1>Login</h1>
<form name="user_login" method="post" action="<?=SITE_URL?>?page=user&cmd=login">
<table width="400" border="0" cellpadding="0" cellspacing="0" id="content-table">
    <tr>
      	<td colspan="2" id="table-heading">User Login</td>
    </tr>
    <tr>
    	<th width="25%">Email</th>
    	<td width="75%"><input name="email" id="email" type="text" /></td>
    </tr>
    <tr>
    	<th>Password</th>
    	<td><input type="password" name="password" autocomplete="off" /></td>
    </tr>
    	<th>&nbsp;</th>
    	<td><a href="<?=SITE_URL?>?page=forgot_password">Forgot Password?</a></td>
    </tr>
    <tr>
    	<td colspan="2" align="center"><input name="submit" id="submit" value="Login" type="submit" /></td>
    </tr>
</table>
</form>

