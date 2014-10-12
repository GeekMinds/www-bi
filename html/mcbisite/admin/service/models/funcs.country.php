<?php
error_reporting(E_ALL ^ E_NOTICE);
/*
*/
//You can getLastInsertion


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\ SITE OPERATIONS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

//You can get the list sites with filter options
function listCountryDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'name asc ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';	  
	$sql = "";
	$sql_size = "";
	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id Value, name_edit DisplayText,id,name_edit as name,code_edit as code,area_code_edit as area_code, flagcountry_edit as flagcountry,alias_edit as alias FROM [".$db_name."].[dbo].[country] where active_edit=1)
					AS user_with_numbers
				WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT  id Value, name DisplayText,id,name_edit as name,code_edit as code,area_code_edit as area_code, flagcountry_edit as flagcountry,alias_edit as alias FROM [".$db_name."].[dbo].[country] where active_edit=1";
	}
	
	$sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[country] where active_edit=1"; 
	
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
function listCountryDataBaseDropDown($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'name asc ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';	  
	$sql = "";
	$sql = "SELECT  id Value, name DisplayText,id,name_edit as name,code_edit as code,area_code_edit as area_code, flagcountry_edit as flagcountry,alias_edit as alias FROM [".$db_name."].[dbo].[country] where active_edit=1 AND active=1 AND id  in (Select distinct country_id from [".$db_name."].[dbo].[site] where country_id != 6) OR (active_edit=1 AND active=0) OR (active_edit=0 AND active=0)";
	$result = $db->sql_query($sql);
	$result= $db->sql_fetchrowset($result);
	return $result;
}

//You can create site
function createCountryDataBase($parameters) {
    global $db, $db_table_prefix,$db_name;
    $name=$db->sql_escape($parameters['name']);
    $code=$db->sql_escape($parameters['code']);
    $area_code=$db->sql_escape($parameters['area_code']);
    $alias=$db->sql_escape($parameters['alias']);
    $flagcountry=$db->sql_escape($parameters['flagcountry']);

    $sql = "
    INSERT INTO [".$db_name."].[dbo].[country]
    	(
			name_edit,code_edit,area_code_edit,alias_edit,flagcountry_edit,active_edit,active
		)
	VALUES(
		'{$name}','{$code}','{$area_code}','{$alias}','{$flagcountry}',1,0)";
			

    $result = $db->sql_query($sql);
    
    
    if ($result) 
    {
              
		$sentencia="SELECT id,name_edit as name,code_edit as code,area_code_edit as area_code, flagcountry_edit as flagcountry,alias_edit as alias FROM country WHERE id=@@IDENTITY";
		$data = $db->sql_query($sentencia);
		$data = $db->sql_fetchrow($data);
                $row = $data['id'];
                InsertNotificationForm("Se ha creado un nuevo pais: ".$name,$row);  
        return $data;
    }
    return false;
}

//You can update sites
function updateCountryDataBase($parameters) {
    global $db, $db_table_prefix, $db_name;
    $id=($parameters['id']);
    $name=$db->sql_escape($parameters['name']);
    $code=$db->sql_escape($parameters['code']);
    $area_code=$db->sql_escape($parameters['area_code']);
    $alias=$db->sql_escape($parameters['alias']);
    $flagcountry=$db->sql_escape($parameters['flagcountry']);

    $sql = "
    UPDATE[{$db_name}].[dbo].[country]
    SET
    	name_edit='{$name}',
    	code_edit='{$code}',
    	area_code_edit='{$area_code}',
    	alias_edit='{$alias}',
    	flagcountry_edit='{$flagcountry}'
    WHERE
    	id={$id}
	";
    $result = $db->sql_query($sql);
    if($result){
    	$sql="SELECT id,name_edit as name,code_edit as code,area_code_edit as area_code_edit,alias_edit as alias,flagcountry_edit as flagcountry FROM [{$db_name}].[dbo].[country] WHERE id=".$id;
    	$result = $db->sql_query($sql);
    	$result = $db->sql_fetchrow($result);
        $row = $result['name'];
        InsertNotificationForm("Se ha actualizado la informaci&oacute;n del pa&iacute;s: ".$row,$id); 
    }
    return $result;
}


//You can delete sites
function deleteCountryDataBase($parameters) {
    global $db, $db_table_prefix, $db_name;
    $id=($parameters['id']);
    ///var/www/html/mcbisite/admin/service/models
    ///var/www/html/mcbisite/assets/images/countries

//    $sql="SELECT flagcountry FROM [{$db_name}].[dbo].[country] WHERE id=".$id;
    
//    $file='/var/www/html/mcbisite/assets/images/countries/'.$result['flagcountry'];
//
//    if(file_exists($file)){
//    	unlink($file);error_log($file);
//    }

    $sql = "UPDATE [{$db_name}].[dbo].[country] set active_edit=0 WHERE id={$id}";
    $result = $db->sql_query($sql);
    
    $sql="SELECT name_edit FROM [{$db_name}].[dbo].[country] WHERE id=".$id;
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrow($result);
    InsertNotificationForm("Se ha eliminado un pa&iacute;s: ".$result['name_edit'],$id);
    


    return ($result);
}
function InsertNotificationForm($msg,$id){
    
    global $db,$loggedInUser;
    $sql = "EXECUTE [dbo].[EditedFormNoContent]"
            . "@editor =" . $db->sql_escape($loggedInUser->user_id) . ""
            . ",@id =" . $db->sql_escape($id) . ""
            . ",@mod_type =7"
            . ",@description='".$msg."'";
    $db->sql_query($sql);
}
function ApprovedModuleCountry($parameters = array()){
    global $db, $db_table_prefix, $db_name;
    $id = $parameters['id_change'];
    $sql = "UPDATE [".$db_name."].[dbo].[country] set name=name_edit,code=code_edit,area_code=area_code_edit,alias=alias_edit,flagcountry=flagcountry_edit,active=active_edit where id=".$id;
    $db->sql_query($sql);
    $sql = "DELETE FROM [".$db_name."].[dbo].[country] where active=0 AND active_edit=0 AND id=".$id;
    $db->sql_query($sql);
}
function DisapprovedModuleCountry($parameters = array()){
    global $db, $db_table_prefix, $db_name;
    $id = $parameters['id_change'];
    $sql = "UPDATE [".$db_name."].[dbo].[country] set name_edit=name,code_edit=code,area_code_edit=area_code,alias_edit=alias,flagcountry_edit=flagcountry,active_edit=active where id=".$id;
    $db->sql_query($sql);
    $sql = "DELETE FROM [".$db_name."].[dbo].[country] where active=0 AND active_edit=0 AND id=".$id;
    $db->sql_query($sql);
}
?>