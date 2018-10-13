<?php
require(SRV_ROOT.'config/form_builder.php');

function responce($post_array)
{
	//print_r($post_array);exit;
	if(!isset($_GET['sid']) or $_GET['sid'] == '') return;
	$survey_id = base64De($_GET['sid']);
	
	$get_survey_q = "SELECT * FROM ".TBL_SURVEY." WHERE id = ".$survey_id;
	$get_survey_r = db_query($get_survey_q);
	$get_survey = db_fetch_array($get_survey_r);
	
	$form = new Formbuilder(array('form_structure' => $get_survey['form_structure']));
	$results = $form->process();
	
	if($results['success'] != true){ 
		$_SESSION['page_errors'] = $results['errors'];
		return;
	}
	
	$u_id = 0;
	if(check_login() == TRUE) $u_id = $_SESSION['user_array']['user_id'];
	$insrt = "INSERT INTO ".TBL_SURVEY_RESPONSES." (user_id, survey_id, response, created_at) 
	VALUES ($u_id, '$survey_id', '".json_encode($results['results'])."', NOW())";

	if(!db_query($insrt))
	{
		add_alert('Unable to save survey data, Please try later');
	}
	else{
		empty_page_data();
		add_alert('Your survey responce has been saved', 'G');
	}
}

// these function is being called through ajax
// do not use add_alert() function. use echo
function load()
{
	if(!isset($_GET['sid']) or $_GET['sid'] == '') exit;
	$survey_id = base64De($_GET['sid']);
	
	$get_survey_q = "SELECT * FROM ".TBL_SURVEY." WHERE id = ".$survey_id;
	$get_survey_r = db_query($get_survey_q);
	$get_survey = db_fetch_array($get_survey_r);
	$survey_id = $get_survey['id'];
	$responses = 0;
	
	$get_response_q = "SELECT * FROM ".TBL_SURVEY_RESPONSES." WHERE survey_id = ".$survey_id;
	$get_response_r = db_query($get_response_q);
	if(db_num_rows($get_response_r) > 0)
	{
		$survey_id = '';
		$responses = db_num_rows($get_response_r);
	}
	
	header("Content-Type: application/json");
	$ret = array("form_id"=>$survey_id, "form_structure"=>json_decode($get_survey['form_structure']), 'responses'=>$responses);
	print json_encode( $ret );
				
	exit;
}

function save($post_array)
{
	if(!isset($post_array['form_title']) or $post_array['form_title'] == '') exit(json_encode(array('message'=>'Form title is required')));
	if(!isset($post_array['frmb']) or $post_array['frmb'] == '') exit(json_encode(array('message'=>'Please add some items to survey')));
	if(check_login() == false) exit;
	
	$form = new Formbuilder($post_array);
	$for_db = $form->get_encoded_form_array();

	$u_id = $_SESSION['user_array']['user_id'];
	if($post_array['form_id'] == "undefined") {
		$query = "INSERT INTO ".TBL_SURVEY." (user_id, title, form_structure, status_list_id, created_at, expired_at) 
		VALUES ($u_id, '".$post_array['form_title']."', '".$for_db['form_structure']."', ".$post_array['status'].", NOW(), '".$post_array['expired_at']."')";
	}
	else{
		$query = "UPDATE ".TBL_SURVEY." SET  
		title = '".$post_array['form_title']."',
		form_structure = '".$for_db['form_structure']."',
		status_list_id = ".$post_array['status'].",
		expired_at = '".$post_array['expired_at']."' 
		WHERE id = ".$post_array['form_id'];
	}
	
	if(!db_query($query))
	{
		echo json_encode(array('message'=>'Unable to save survey form, Please try later'));
	}
	else{
		//empty_page_data();
		if($post_array['form_id'] == "undefined") $form_id = db_insert_id();
		else $form_id = $post_array['form_id'];
		echo json_encode(array('message'=>'Your survey has been saved', 'id'=>$form_id));
	}
	exit;
}

function ajax_list()
{
	if(check_login() == false) exit;
	$u_id = $_SESSION['user_array']['user_id'];
	$dbColumns = array('id', 'title', 'created_at', 'expired_at');
	$sTable = TBL_SURVEY;
	$sIndexColumn = "id";
		
	//Paging
	$sLimit = "";
	if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
	{
		$sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];
	}
	
	//Ordering
	$sOrder = '';
	if(isset($_GET['iSortCol_0']))
	{
		$sOrder = "ORDER BY  ";
		for($i=0; $i<intval($_GET['iSortingCols']); $i++)
		{
			if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
				$sOrder .= $dbColumns[intval($_GET['iSortCol_'.$i])]." ".$_GET['sSortDir_'.$i].", ";
			}
		}
		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY") $sOrder = "";
	}
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = 'WHERE user_id = '.$u_id;
	if(isset($_GET['sSearch']) && $_GET['sSearch'] != "")
	{
		if($sWhere == "") $sWhere = "WHERE (";
		else $sWhere .= " AND ";
		for($i=0; $i<count($aColumns); $i++)
		{
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".$_GET['sSearch']."%' OR ";
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}
	
	/* Individual column filtering */
	if(!isset($aColumns) or $aColumns == '') $aColumns = array();
	for($i=0; $i<count($aColumns); $i++)
	{
		if(isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '')
		{
			if($sWhere == "") $sWhere = "WHERE ";
			else $sWhere .= " AND ";
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".$_GET['sSearch_'.$i]."%' ";
		}
	}

	
	//SQL queries Get data to display
	$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $dbColumns))."
		FROM $sTable
		$sWhere
		$sOrder
		$sLimit";
	$rResult = db_query($sQuery);
	
	/* Data set length after filtering */
	$sQuery = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = db_query($sQuery);
	$aResultFilterTotal = db_fetch_array($rResultFilterTotal, MYSQL_NUM);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "SELECT COUNT(".$sIndexColumn.") FROM $sTable ";
	$rResultTotal = db_query( $sQuery);
	$aResultTotal = db_fetch_array($rResultTotal, MYSQL_NUM);
	$iTotal = $aResultTotal[0];
	
	//Output
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	while($aRow = db_fetch_array( $rResult ))
	{
		$row = array();
		for($i=0; $i<count($dbColumns); $i++)
		{
			if($dbColumns[$i] == 'created_at') $row[] = date("d-m-Y", strtotime($aRow[$dbColumns[$i]]) );
			else if($dbColumns[$i] == 'expired_at') $row[] = date("d-m-Y", strtotime($aRow[$dbColumns[$i]]) );
			else $row[] = $aRow[$dbColumns[$i]];
		}
		$row[] = '<a href="'.SITE_URL.'?page=edit_survey&sid='.base64En($aRow['id']).'">Edit</a>&nbsp;&nbsp;
		<a href="'.SITE_URL.'?page=survey_responces&sid='.base64En($aRow['id']).'">Conduct Results</a>';
		$output['aaData'][] = $row;
	}

	$json_output = json_encode($output);
	echo $json_output;
	exit;
}

function home_list()
{
	$dbColumns = array('id', 'title', 'created_at');
	$sTable = TBL_SURVEY;
	$sIndexColumn = "id";
		
	//Paging
	$sLimit = "";
	if(isset($_GET['iDisplayStart']) && $_GET['iDisplayLength'] != '-1')
	{
		$sLimit = "LIMIT ".$_GET['iDisplayStart'].", ".$_GET['iDisplayLength'];
	}
	
	//Ordering
	$sOrder = '';
	if(isset($_GET['iSortCol_0']))
	{
		$sOrder = "ORDER BY  ";
		for($i=0; $i<intval($_GET['iSortingCols']); $i++)
		{
			if($_GET['bSortable_'.intval($_GET['iSortCol_'.$i])] == "true"){
				$sOrder .= $dbColumns[intval($_GET['iSortCol_'.$i])]." ".$_GET['sSortDir_'.$i].", ";
			}
		}
		$sOrder = substr_replace($sOrder, "", -2);
		if ($sOrder == "ORDER BY") $sOrder = "";
	}
	
	/* 
	 * Filtering
	 * NOTE this does not match the built-in DataTables filtering which does it
	 * word by word on any field. It's possible to do here, but concerned about efficiency
	 * on very large tables, and MySQL's regex functionality is very limited
	 */
	$sWhere = 'WHERE status_list_id = 1 AND expired_at > '.date('Y-m-d');
	if(isset($_GET['sSearch']) && $_GET['sSearch'] != "")
	{
		if($sWhere == "") $sWhere = "WHERE (";
		else $sWhere .= " AND (";
		for($i=0; $i<count($aColumns); $i++)
		{
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".$_GET['sSearch']."%' OR ";
		}
		$sWhere = substr_replace($sWhere, "", -3);
		$sWhere .= ')';
	}
	
	// Individual column filtering 
	if(!isset($aColumns) or $aColumns == '') $aColumns = array();
	for($i=0; $i<count($aColumns); $i++)
	{
		if(isset($_GET['bSearchable_'.$i]) && $_GET['bSearchable_'.$i] == "true" && $_GET['sSearch_'.$i] != '')
		{
			if($sWhere == "") $sWhere = "WHERE ";
			else $sWhere .= " AND ";
			$sWhere .= "`".$aColumns[$i]."` LIKE '%".$_GET['sSearch_'.$i]."%' ";
		}
	}

	
	//SQL queries Get data to display
	$sQuery = "SELECT SQL_CALC_FOUND_ROWS ".str_replace(" , ", " ", implode(", ", $dbColumns))."
		FROM $sTable
		$sWhere
		$sOrder
		$sLimit";
	$rResult = db_query($sQuery);
	
	/* Data set length after filtering */
	$sQuery = "SELECT FOUND_ROWS()";
	$rResultFilterTotal = db_query($sQuery);
	$aResultFilterTotal = db_fetch_array($rResultFilterTotal, MYSQL_NUM);
	$iFilteredTotal = $aResultFilterTotal[0];
	
	/* Total data set length */
	$sQuery = "SELECT COUNT(".$sIndexColumn.") FROM $sTable ";
	$rResultTotal = db_query( $sQuery);
	$aResultTotal = db_fetch_array($rResultTotal, MYSQL_NUM);
	$iTotal = $aResultTotal[0];
	
	//Output
	$output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => $iTotal,
		"iTotalDisplayRecords" => $iFilteredTotal,
		"aaData" => array()
	);
	
	$fIndex = 0;
	while($aRow = db_fetch_array( $rResult ))
	{
		$row = array();
		$fIndex++;
		$row[] = $fIndex;
		for($i=0; $i<count($dbColumns); $i++)
		{
			if($dbColumns[$i] == 'id') continue;
			if($dbColumns[$i] == 'created_at') $row[] = date("d-m-Y", strtotime($aRow[$dbColumns[$i]]) );
			else $row[] = $aRow[$dbColumns[$i]];
		}
		$row[] = '<a href="'.SITE_URL.'?page=survey_conduct&sid='.base64En($aRow['id']).'">Conduct Survey</a>';
		$output['aaData'][] = $row;
	}

	$json_output = json_encode($output);
	echo $json_output;
	exit;
}
?>