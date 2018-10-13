
<link href="<?=SITE_URL?>assets/data_tables/css/jquery.dataTables.css" rel="stylesheet" type="text/css">
<script src="<?=SITE_URL?>assets/data_tables/js/jquery.dataTables.min.js"></script>

<a href="<?=SITE_URL?>?page=add_new_user" style="margin:15px;line-height:30px;border:1px solid #D0D0D0;display:inline-block;
box-shadow:1px 1px 3px #CCC;padding:0px 8px;color:#999;">Add New User</a>

<script type="text/javascript">
$(document).ready(function() {
	$('#user_list').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "<?=SITE_URL?>?page=user&cmd=ajax_list"
	} );
} );
</script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="user_list">
	<thead>
		<tr>
        	<th width="5%">ID</th>
			<th width="15%">First Name</th>
			<th width="15%">Last Name</th>
            <th width="20%">Email</th>
            <th width="10%">Gender</th>
			<th width="15%">Created Date</th>
            <th width="20%">Options</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="7" class="dataTables_empty">Loading data from server</td>
		</tr>
	</tbody>
</table>