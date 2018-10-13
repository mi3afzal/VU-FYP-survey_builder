
<link href="<?=SITE_URL?>assets/data_tables/css/jquery.dataTables.css" rel="stylesheet" type="text/css">
<script src="<?=SITE_URL?>assets/data_tables/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
	$('#survey_list').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "<?=SITE_URL?>?page=survey&cmd=home_list",
		'sPaginationType': 'full_numbers',
		'bAutoWidth': true,
		'aoColumns'			: [ 
			{'sName':'id', 'bSortable':false, 'bSearchable':false},
			{'sName':'title'},
			{'sName':'created_at', 'bSearchable':false},          
			{'sName':'', 'bSortable':false, 'bSearchable':false}
		],
	} );
} );
</script>
<h2 class="title"><span>Welcome to my website! this website is designed to conduct surveys on different topics.</span></h2>
<br /><br />

<table cellpadding="0" cellspacing="0" border="0" class="display" id="survey_list">
	<thead>
		<tr>
        	<th width="10%">ID</th>
			<th width="50%">Title</th>
			<th width="20%">Created Date</th>
            <th width="20%">Options</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="4" class="dataTables_empty">Loading data from server</td>
		</tr>
	</tbody>
</table>