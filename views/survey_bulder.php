
<link href="<?=SITE_URL?>assets/data_tables/css/jquery.dataTables.css" rel="stylesheet" type="text/css">
<script src="<?=SITE_URL?>assets/data_tables/js/jquery.dataTables.min.js"></script>

<a href="<?=SITE_URL?>?page=build_new_survey" class="button">Build New Survey</a>

<script type="text/javascript">
$(document).ready(function() {
	$('#survey_list').dataTable( {
		"bProcessing": true,
		"bServerSide": true,
		"sAjaxSource": "<?=SITE_URL?>?page=survey&cmd=ajax_list",
		'sPaginationType': 'full_numbers',
		'bAutoWidth': true,
		'aoColumns'			: [ 
			{'sName':'id', 'bSortable':false, 'bSearchable':false},
			{'sName':'title'},
			{'sName':'created_at'},    
			{'sName':'expired_at'},      
			{'sName':'', 'bSortable':false, 'bSearchable':false}
		],
	} );
} );
</script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="survey_list">
	<thead>
		<tr>
			<th width="5%">ID</th>
			<th width="45%">Title</th>
			<th width="15%">Created Date</th>
            <th width="15%">Expired Date</th>
            <th width="20%">Options</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td colspan="4" class="dataTables_empty">Loading data from server</td>
		</tr>
	</tbody>
</table>