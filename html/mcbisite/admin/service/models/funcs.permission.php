<?php
error_reporting(E_ALL ^ E_NOTICE);
/*
*/
//You can getLastInsertion
function getLastInsertionPermission() {
    global $db, $emailActivation, $websiteUrl, $db_table_prefix;
    $sql = "SELECT SCOPE_IDENTITY() last_intertion";
    $result = $db->sql_query($sql);
	$row = $db->sql_fetchrow($result);
    return $row['last_intertion'];
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\ SITE OPERATIONS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

//You can get the list sites with filter options
function listPermissionDataBase_bck($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'admin.id DESC ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
	$parameters['admin_id'] = (isset($parameters['admin_id'])) ? $parameters['admin_id'] : '';
	
	$sql = "";
	
	$sql_size = "";
	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(
					
					SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row,						 
						CASE
							WHEN admin.group_id=2 
								THEN ps.id 
							WHEN admin.group_id=3 
								THEN sec.id 
							WHEN admin.group_id=3 
								THEN cont.id
							ELSE
								0
						END AS permission_id,
						admin.id admin_id,
						admin.name,
						ps.site_id,
						sec.section_id,
						cont.content_id,
						'' page_id
					 FROM 
					 	[".$db_name."].[dbo].[admin] admin
					 	LEFT JOIN [".$db_name."].[dbo].[permission_site] ps
						ON admin.id=ps.admin_id
						
						LEFT JOIN [".$db_name."].[dbo].[permission_section] sec
						ON admin.id=sec.admin_id
						
						LEFT JOIN [".$db_name."].[dbo].[permission_content] cont
						ON admin.id=cont.admin_id
						
						WHERE 
							admin.id = ".$db->sql_escape($parameters['admin_id']) . "
					 )
					AS user_with_numbers
				WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT 
					CASE
						WHEN admin.group_id=2 
							THEN ps.id 
						WHEN admin.group_id=3 
							THEN sec.id 
						WHEN admin.group_id=3 
							THEN cont.id
						ELSE
							0
				    END AS permission_id,
					   admin.id admin_id,
					   admin.name,
					   ps.site_id,
					   sec.section_id,
					   cont.content_id,
					   '' page_id
				FROM
					[".$db_name."].[dbo].[admin] admin
				LEFT JOIN [".$db_name."].[dbo].[permission_site] ps
				ON admin.id=ps.admin_id
				
				LEFT JOIN [".$db_name."].[dbo].[permission_section] sec
				ON admin.id=sec.admin_id
				
				LEFT JOIN [".$db_name."].[dbo].[permission_content] cont
				ON admin.id=cont.admin_id
				
				WHERE 
					admin.id = ".$db->sql_escape($parameters['admin_id']);
					
	}
	
	$sql_count = "SELECT COUNT(*) total
					FROM
						[".$db_name."].[dbo].[admin] admin
					LEFT JOIN [".$db_name."].[dbo].[permission_site] ps
					ON admin.id=ps.admin_id
					
					LEFT JOIN [".$db_name."].[dbo].[permission_section] sec
					ON admin.id=sec.admin_id
					
					LEFT JOIN [".$db_name."].[dbo].[permission_content] cont
					ON admin.id=cont.admin_id
					
					WHERE 
						admin.id = ".$db->sql_escape($parameters['admin_id']);
	
	$result = $db->sql_query($sql);
	$result_count = $db->sql_query($sql_count);

	$result= $db->sql_fetchrowset($result);
	$result_count= $db->sql_fetchrow($result_count);
	$data["rows"] = $result;
	$data["count"] = $result_count['total'];
	
	return $data;
}


function listPermissionDataBase($parameters = array()){
    global $db, $db_table_prefix, $db_name;
	
	error_log(json_encode($parameters));
	
    $data = array();
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'admin.id DESC ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
	$parameters['admin_id'] = (isset($parameters['admin_id'])) ? $parameters['admin_id'] : '';
	$parameters['group_id'] = (isset($parameters['group_id'])) ? $parameters['group_id'] : '';

	$parameters['group_id'] = (int)$parameters['group_id'];
	
	
	$sql = "";
	
	error_log("GRUPO:: ".$parameters['group_id']);
	switch($parameters['group_id']){
		case SUPER_ADMINISTRADOR:
			$sql = "";
		break;
		
		case ADMINISTRADOR_REGIONAL:
			$sql = "SELECT 
					   perm.id  permission_id,
					   perm.site_id site_id,
					   'Todas' section_id,
					   'Todas' page_id,
					   'Todos' content_id
				FROM
					 [".$db_name."].[dbo].[permission_site] perm
				
				WHERE 
					perm.admin_id = ".$db->sql_escape($parameters['admin_id']) ." ";
		break;
		
		case GESTOR:
			$sql = "SELECT 
					   perm.id  permission_id,
					   section.id section_id,
					   h.site_id,
					   'Todas' page_id,
					   'Todos' content_id
				FROM
					 [".$db_name."].[dbo].[permission_section] perm,
					 [".$db_name."].[dbo].[mod_c] section,
					 [".$db_name."].[dbo].[header] h
				
				WHERE 
					perm.admin_id = ".$db->sql_escape($parameters['admin_id']) ." AND
					section.id = perm.section_id AND
					h.content_id = section.content_id ";
				
		break;
		
		case EDITOR:
			$sql = "SELECT 
					   perm.id  permission_id, 
					   section.id section_id,
					   h.site_id,
					   pc.page_id,
					   pc.content_id
				FROM
					 [".$db_name."].[dbo].[permission_content] perm,
					 [".$db_name."].[dbo].[mod_c] section,
					 [".$db_name."].[dbo].[header] h,
					 [".$db_name."].[dbo].[mod_c_submenu] submenu,
					 [".$db_name."].[dbo].[page_content] pc
				WHERE 
					perm.admin_id = ".$db->sql_escape($parameters['admin_id']) ." AND
					pc.content_id = perm.content_id AND
					submenu.link = CONCAT('./?page=',pc.page_id) AND
					section.id = submenu.mod_c_id AND
					h.content_id = section.content_id ";
		break;
		
		case ANALISTA:
			$sql = "";
		break;
	}
    $result = $db->sql_query($sql);	
	$result= $db->sql_fetchrowset($result);
	$data["rows"] = $result;
	$data["count"] = count($result);
	return $data;
}


//You can create site
function createPermissionDataBase($parameters = array()) {	
    global $db, $db_table_prefix, $db_name, $loggedInUser;
	
	$parameters['group_id'] = (isset($parameters['group_id'])) ? $parameters['group_id'] : '';
	$parameters['group_id'] = (int)$parameters['group_id'];
	
	$sql = "";
	switch($parameters['group_id']){
		case SUPER_ADMINISTRADOR:
			$sql = "";
		break;
		
		case ADMINISTRADOR_REGIONAL:
			$sql = "INSERT INTO [".$db_name."].[dbo].[permission_site] (
						admin_id,
						site_id,
						created_at
						)
					VALUES (
						'" . $db->sql_escape($parameters["admin_id"]) . "',
						'" . $db->sql_escape($parameters["site_id"]) . "',
						GetDate()
						)";
		break;
		
		case GESTOR:
			$sql = "INSERT INTO [".$db_name."].[dbo].[permission_section] (
						admin_id,
						section_id,
						created_at
						)
					VALUES (
						'" . $db->sql_escape($parameters["admin_id"]) . "',
						'" . $db->sql_escape($parameters["section_id"]) . "',
						GetDate()
						)";
		break;
		
		case EDITOR:
			$sql = "INSERT INTO [".$db_name."].[dbo].[permission_content] (
						admin_id,
						content_id,
						created_at
						)
					VALUES (
						'" . $db->sql_escape($parameters["admin_id"]) . "',
						'" . $db->sql_escape($parameters["content_id"]) . "',
						GetDate()
						)";
		break;
		
		case ANALISTA:
			$sql = "";
		break;
	}
    $result = $db->sql_query($sql);
	$parameters["permission_id"] = $db->sql_nextid();
    if ($result) {
		return $parameters;
    }
    return false;
}

//You can update sites
function updatePermissionDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $sql = "UPDATE [".$db_name."].[dbo].[question]  SET 
					question_es = '" . $db->sql_escape($parameters["question_es"]) . "', 
					question_en = '" . $db->sql_escape($parameters["question_en"]) . "'
			WHERE id = " . $db->sql_escape($parameters["question_id"]) . ";";
	//return $sql;
    $result = $db->sql_query($sql);
	insertTags($parameters);
    return $result;
}

 
function getDefaultPages($parameters=array()){
	global $db, $db_table_prefix, $db_name, $site_id;
    $data = array();

    $sql = "SELECT id, alias, page_id
			FROM [".$db_name."].[dbo].[default_page] 
			WHERE site_id = " . $db->sql_escape($site_id) . " ";

    $result = $db->sql_query($sql);
    $rows = $db->sql_fetchrowset($result);
    $data = $rows;
    return $data;
}


//You can delete sites
function deletePermissionDataBase($parameters = array()) {
    /*global $db, $db_table_prefix, $db_name;
    $sql = "DELETE FROM  [".$db_name."].[dbo].[question]  WHERE id = " . $db->sql_escape($parameters["question_id"]);
    $result = $db->sql_query($sql);
    return ($result);*/
	return false;
}


?>