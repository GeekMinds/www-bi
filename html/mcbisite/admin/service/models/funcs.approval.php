<?php

error_reporting(E_ALL ^ E_NOTICE);
/*
*/
//You can getLastInsertion


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\ SITE OPERATIONS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

//You can get the list sites with filter options
function listApprovalDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $loggedInUser;
	
    $data = array();
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id DESC ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';	  
	$sql = "";
	$sql_size = "";
	$where_notification="";
    if($loggedInUser->group_id!=SUPER_ADMINISTRADOR){
    	$where_notification=" AND pc.page_id IN (".getPermissionNotification($loggedInUser->group_id).")";
    }
	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(
						SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, 
								ap.id Value, 
								m.description DisplayText,
								m.name module_type,
								ap.id ,
								CONCAT('Se realizaron cambios en un m&oacute;dulo  <b>',m.description, '</b>') message,
								ap.content_id,
								ap.approved_by,
								ap.id_change,
								CONVERT(VARCHAR(19), ap.approval_date, 100) approval_date,
								pc.page_id,
								ap.status
						FROM    [".$db_name."].[dbo].[page_content] pc RIGHT JOIN [".$db_name."].[dbo].[content] c on  pc.content_id = c.id,
							[".$db_name."].[dbo].[approval_m] ap,
							
							[".$db_name."].[dbo].[module_list] m 
	
						WHERE 
							c.id = ap.content_id AND 
							m.id = c.module_id AND 
							pc.content_id = c.id AND 
                            ap.form = 0 AND 
							ap.approved_by IS NULL".$where_notification."
							
					)
					AS user_with_numbers
				WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT  ap.id Value, 
						m.description DisplayText,
						m.name module_type,
						ap.id,
						CONCAT('Se realizaron cambios en un m&oacute;dulo <b>',m.description,'</b>') message,
						ap.content_id,
						ap.approved_by,
						ap.id_change,
						CONVERT(VARCHAR(19), ap.approval_date, 100) approval_date,
						pc.page_id,
						ap.status
				 FROM  
                            [".$db_name."].[dbo].[page_content] pc RIGHT JOIN [".$db_name."].[dbo].[content] c on  pc.content_id = c.id, 
							[".$db_name."].[dbo].[approval_m] ap,
							[".$db_name."].[dbo].[module_list] m 

						WHERE 
							c.id = ap.content_id AND 
							m.id = c.module_id AND 
                            ap.form = 0 AND 
							ap.approved_by IS NULL".$where_notification;
	}
	
	$sql_count = "SELECT COUNT(*) total FROM  
                            [".$db_name."].[dbo].[page_content] pc RIGHT JOIN [".$db_name."].[dbo].[content] c on  pc.content_id = c.id, 
							[".$db_name."].[dbo].[approval_m] ap,
							[".$db_name."].[dbo].[module_list] m 

						WHERE 
							c.id = ap.content_id AND 
							m.id = c.module_id AND 
                            ap.form = 0 AND 
							ap.approved_by IS NULL".$where_notification; 
	if($parameters['jtsorting']!=""){
		$sql .=  " ORDER BY ".$parameters['jtsorting'];
	}	
	
	//error_log("La consulta>".$sql);
	$result = $db->sql_query($sql);
	$result_count = $db->sql_query($sql_count);

	$result= $db->sql_fetchrowset($result);
	$result_count= $db->sql_fetchrow($result_count);
	//dumpear($result);
	$data["rows"] = $result;
	$data["count"] = $result_count['total'];
	
	return $data;
}

function listApprovalDataBaseForma($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $loggedInUser;
	
    $data = array();
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id DESC ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';	  
	$sql = "";
	$sql_size = "";

	$where_notification="";
    if($loggedInUser->group_id!=SUPER_ADMINISTRADOR){
    	$where_notification=" AND ap.mod_type IN(2) AND ap.id_change IN (".getPermissionNotificationForm($loggedInUser->group_id).")";
    }

	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(
						SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, 
								ap.id Value, 
                                                                ap.id_change,
								ap.id,
								ap.content_id,
								ap.approved_by,
								ap.mod_type as module_type,
								CONVERT(VARCHAR(19), ap.approval_date, 100) approval_date,
								ap.status
						FROM    
							[".$db_name."].[dbo].[approval_m] ap
							
	
						WHERE
							ap.form = 1 AND
							ap.approved_by IS NULL".$where_notification."
							
					)
					AS user_with_numbers
				WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT  ap.id Value, 
                                                                ap.id_change,
								ap.id,
								ap.content_id,
								ap.approved_by,
								ap.mod_type as module_type,
								CONVERT(VARCHAR(19), ap.approval_date, 100) approval_date,
								ap.status
				 FROM  
							[".$db_name."].[dbo].[approval_m] ap

						WHERE
							ap.form = 1 AND
							ap.approved_by IS NULL".$where_notification;
	}
	
	$sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[approval_m] ap WHERE form=1 AND ap.approved_by IS NULL".$where_notification; 
	
	if($parameters['jtsorting']!=""){
		$sql .=  " ORDER BY ".$parameters['jtsorting'];
	}	
	//error_log("La consulta>".$sql);
	$result = $db->sql_query($sql);
	$result_count = $db->sql_query($sql_count);

	$result= $db->sql_fetchrowset($result);
	$result_count= $db->sql_fetchrow($result_count);
	//dumpear($result);
	$data["rows"] = $result;
	$data["count"] = $result_count['total'];
	
	return $data;
}


//You can get the list sites with filter options
function listApprovalDetailDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $loggedInUser;
	
    $data = array();
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id DESC ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
	$parameters['approval_id'] = (isset($parameters['approval_id'])) ? $parameters['approval_id'] : '0';	  
	$sql = "";
	$sql_size = "";
	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(
						SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, 
								ap.id Value, 
								ap.description DisplayText,
								ap.approval_m  approval_id,
								ap.id detail_id,
								ap.description detail_description,
								ap.editor_id,
								CONVERT(VARCHAR(19), ap.edit_date, 100) edit_date
						FROM 
							[".$db_name."].[dbo].[approval_d] ap
						WHERE
							ap.approval_m = ".$db->sql_escape($parameters['approval_id'])."
							
					)
					AS user_with_numbers
				WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT  ap.id Value, 
								ap.description DisplayText,
								ap.approval_m  approval_id,
								ap.id detail_id,
								ap.description detail_description,
								ap.editor_id,
								CONVERT(VARCHAR(19), ap.edit_date, 100) edit_date
				 FROM  
							[".$db_name."].[dbo].[approval_d] ap
						WHERE
							ap.approval_m = ".$db->sql_escape($parameters['approval_id'])."";
	}
	
	$sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[approval_d] ap
						WHERE
							ap.approval_m = ".$db->sql_escape($parameters['approval_id']).""; 
	
	if($parameters['jtsorting']!=""){
		$sql .=  " ORDER BY ".$parameters['jtsorting'];
	}	
	//error_log("La consulta>".$sql);
	$result = $db->sql_query($sql);
	$result_count = $db->sql_query($sql_count);

	$result= $db->sql_fetchrowset($result);
	$result_count= $db->sql_fetchrow($result_count);
	//dumpear($result);
	$data["rows"] = $result;
	$data["count"] = $result_count['total'];
	
	return $data;
}





//You can create site
function createApprovalDataBase($parameters) {
    global $db, $db_table_prefix,$db_name, $loggedInUser;
    $content_id=$db->sql_escape($parameters['content_id']);
    $description=$db->sql_escape($parameters['description']);
	$user_id = $loggedInUser->user_id;

    /*$sql = "
    INSERT INTO [".$db_name."].[dbo].[approval_m]
    	(
			content_id,
			description
		)
	VALUES(
		'".$db->sql_escape($parameters['content_id'])."',
		'".$db->sql_escape($parameters['description'])."'
		)";
    $result = $db->sql_query($sql);
	
	
    if ($result) 
    {
		$sentencia="SELECT id FROM approval_m WHERE id=@@IDENTITY";
		$data = $db->sql_query($sentencia);
		$data = $db->sql_fetchrow($data);
		
		$sql = "
		INSERT INTO [".$db_name."].[dbo].[approval_m]
			(
				approval_m,
				editor_id,
				edit_date
			)
		VALUES(
			'".$db->sql_escape($parameters['content_id'])."',
			'".$db->sql_escape($parameters['description'])."'
			)";
		$result = $db->sql_query($sql);
		
        return $data;
    }*/
    return false;
}

//You can update sites
function updateApprovalDataBase($parameters) {
    global $db, $db_table_prefix, $db_name;
    $id=($parameters['id']);
    $approved_by=$db->sql_escape($parameters['approved_by']);

    $sql = "
    UPDATE[{$db_name}].[dbo].[approval_m]
    SET 
		approval_date= GetDate() ,
    	approved_by='{$approved_by}'
    WHERE
    	id={$id}
	";
    $result = $db->sql_query($sql);
    if($result){
    	$sql= "SELECT  ap.id Value, 
								m.name DisplayText,
								ap.content_id,
								ap.approved_by,
								ap.approval_date,
								pc.page_id 
				 FROM  
							[".$db_name."].[dbo].[approval_m] ap,
							[".$db_name."].[dbo].[content] c,
							[".$db_name."].[dbo].[module_list] m,
							[".$db_name."].[dbo].[page_content] pc
						WHERE
							c.id = ap.content_id AND
							m.id = c.module_id AND
							pc.content_id = c.id AND ap.id = ".$db->sql_escape($parameters['id']);
    	$result = $db->sql_query($sql);
    	$result = $db->sql_fetchrow($sql);
    }
    return $result;
}


//You can delete sites
function deleteApprovalDataBase($parameters) {
    global $db, $db_table_prefix, $db_name;
    $id=($parameters['id']);
    $sql = "DELETE FROM [{$db_name}].[dbo].[approval_m] WHERE id={$id}";
    $result = $db->sql_query($sql);
    return ($result);
}


function approveChange($parameters=array()){
    global $db, $db_table_prefix,$db_name, $loggedInUser;
	$abroved_by = $loggedInUser->user_id;
	$content_id = $parameters['content_id'];
	$module_type = $parameters['module_type'];
	$sql = "UPDATE
				[".$db_name."].[dbo].[approval_m]
			SET 
				approval_date= GetDate() ,
				approved_by='".$abroved_by."',
				status = 1
			WHERE
				id=".$db->sql_escape($parameters['id'])." ";
	$result = $db->sql_query($sql);
	
	switch($module_type){
		case "module_text":
			require_once("funcs.module_text.php");
			ApproveModuleTextContent($parameters);
		break;
		case "module_carrousel":
			require_once("funcs.module_carrousel.php");
			ApprovedModuleCarrousel($parameters);
		break;
		case "mod_m":
			require_once("funcs.module_m.php");
			ApprovedModuleMedia($parameters);
		break;
		case "module_issuu":
			require_once("funcs.module_issuu.php");
			ApprovedModuleISSUU($parameters);
		break;
		case "mod_g":
			require_once("funcs.modg.php");
			ApproveModuleG($parameters);
		break;
		case "module_form":
			require_once("funcs.module_form.php");
			ApprovedModuleForm($parameters);
                break;
                case "mod_c":
                        require_once("funcs.modc.php");
                        ApprovedModuleBarNav($parameters);
                break;
                case "mod_k":
                        require_once("funcs.mod_k.php");
                        ApprovedModuleGaleryProduct($parameters);
		break;

		case "mod_p":
		        require_once("funcs.mod_p.php");
		        ApprovedModuleComentario($parameters);

		break;

		case "mod_general_gallery":
			require_once("funcs.general_gallery.php");
			ApprovedModuleGallery($parameters);
		break;

		case "searcher":
			require_once("funcs.searcher.php");
			ApprovedModuleSearch($parameters);
		break;
		
		case "module_search_questions":
			require_once("funcs.module_search_question.php");
			ApprovedModuleBuscador($parameters);
		break;

		case "search_content":
			require_once("funcs.search_content.php");
			ApprovedModuleSearchContent($parameters);

		break;
	}

}
function approveChangeForma($parameters=array()){
    global $db, $db_table_prefix,$db_name, $loggedInUser;
	$abroved_by = $loggedInUser->user_id;
	$content_id = $parameters['content_id'];
	$module_type = $parameters['module_type'];
	$sql = "UPDATE
				[".$db_name."].[dbo].[approval_m]
			SET 
				approval_date= GetDate() ,
				approved_by='".$abroved_by."',
				status = 1
			WHERE
				id=".$db->sql_escape($parameters['id'])." ";
	$result = $db->sql_query($sql);
	
	switch($module_type){
                case "1":
			require_once("funcs.module_a_headers.php");
			ApprovedModuleA($parameters);
		break;
                case "2":
                        require_once("funcs.modc.php");
                        ApprovedModuleBarNavForma($parameters);
                break;
                case "3":
                        require_once("funcs.module_z_interest.php");
                        ApprovedModuleInteres($parameters);
                break;

                case "4":
                		require_once("funcs.site.php");
                		ApprovedModuleSite($parameters);
                break;

                case "5":
                        require_once("funcs.legal.php");
                        ApprovedModuleFooter($parameters);
                break;
                case "6":
                        require_once("funcs.page_content.php");
                       ApprovedModulePageConfiguration($parameters);
                break;
                case "7":
                        require_once("funcs.country.php");
                       ApprovedModuleCountry($parameters);
                break;
                case "8":
                        require_once("funcs.mod_social.php");
                       approveMod_social($parameters);
                break;
	}

}


function disapproveDiscard($parameters=array()){
   	global $db, $db_table_prefix,$db_name, $loggedInUser;
                        
	$abroved_by = $loggedInUser->user_id;
        $module_type = $parameters['module_type'];
	$sql = "UPDATE
				[".$db_name."].[dbo].[approval_m]
			SET 
				approval_date= GetDate() ,
				approved_by='".$abroved_by."',
				status = 2
			WHERE
				id=".$db->sql_escape($parameters['id'])." ";
        $result = $db->sql_query($sql);
	switch($module_type){
		case "module_text":
			require_once("funcs.module_text.php");
			DisapproveModuleTextContent($parameters);
		break;
		case "module_carrousel":
			require_once("funcs.module_carrousel.php");
			DisapprovedModuleCarrousel($parameters);
		break;
		case "mod_m":
			require_once("funcs.module_m.php");
			DisapprovedModuleMedia($parameters);
		break;
		case "module_issuu":
			require_once("funcs.module_issuu.php");
                        DisapprovedModuleISSUU($parameters);
		break;
		case "mod_g":
			require_once("funcs.modg.php");
                        DisapproveModuleG($parameters);
		break;
		case "module_form":
			require_once("funcs.module_form.php");
                        DisapprovedModuleForm($parameters);
                break;
                case "mod_c":
                        require_once("funcs.modc.php");
                        DisapprovedModuleBarNav($parameters);
                break;
                case "mod_k":
                        require_once("funcs.mod_k.php");
                        DisapprovedModuleGaleryProduct($parameters);
		break;

		case "mod_a":
			require_once("funcs.module_a_headers.php");
			DisapprovedModuleA($parameters);
		break;

                case "mod_p":
			require_once("funcs.mod_p.php");
			DisapprovedModuleComentario($parameters);
		break;

		case "mod_general_gallery":
			require_once("funcs.general_gallery.php");
			DisapprovedModuleGallery($parameters);
		break;

		case "searcher":
			require_once("funcs.searcher.php");
			DisapprovedModuleSearch($parameters);
		break;
                
                case "module_search_questions":
			require_once("funcs.module_search_question.php");
			DisapprovedModuleBuscador($parameters);
		break;

		case "search_content":
			require_once("funcs.search_content.php");
			DisapprovedModuleSearchContent($parameters);

		break;


	}
}
function disapproveDiscardForma($parameters=array()){
   	global $db, $db_table_prefix,$db_name, $loggedInUser;
                        
	$abroved_by = $loggedInUser->user_id;
        $module_type = $parameters['module_type'];
	$sql = "UPDATE
				[".$db_name."].[dbo].[approval_m]
			SET 
				approval_date= GetDate() ,
				approved_by='".$abroved_by."',
				status = 2
			WHERE
				id=".$db->sql_escape($parameters['id'])." ";
        $result = $db->sql_query($sql);
	switch($module_type){
                case "1":
			require_once("funcs.module_a_headers.php");
			DisapprovedModuleA($parameters);
		break;
                case "2":
                        require_once("funcs.modc.php");
                        DisapprovedModuleBarNavForma($parameters);
                break;
                case "3":
                        require_once("funcs.module_z_interest.php");
                        DisapprovedModuleInteres($parameters);
                break;

                case "4":
                		require_once("funcs.site.php");
                		DisapprovedModuleSite($parameters);
                break;

                case "5":
                        require_once("funcs.legal.php");
                       DisapprovedModuleFooter($parameters);
                break;
                case "6":
                        require_once("funcs.page_content.php");
                       DisapprovedModulePageConfiguration($parameters);
                break;
                case "7":
                        require_once("funcs.country.php");
                       DisapprovedModuleCountry($parameters);
                break;
                case "8":
                        require_once("funcs.mod_social.php");
                       disapproveMod_social($parameters);
                break;
	}
}

function disapproveNotDiscard($parameters=array()){
    global $db, $db_table_prefix,$db_name, $loggedInUser;
	$abroved_by = $loggedInUser->user_id;
	$sql = "UPDATE
				[".$db_name."].[dbo].[approval_m]
			SET 
				approval_date= GetDate() ,
				approved_by='".$abroved_by."',
				status = 3
			WHERE
				id=".$db->sql_escape($parameters['id'])." ";
        $db->sql_query($sql);
}

?>