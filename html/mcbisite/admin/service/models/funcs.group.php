<?php
error_reporting(E_ALL ^ E_NOTICE);
/*
*/
//You can getLastInsertion


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
//\\ SITE OPERATIONS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

//You can get the list sites with filter options
function listGroupDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name;
    $data = array();
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id asc ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';	  
	$sql = "";
	$sql_size = "";
	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id Value, name DisplayText,id,name FROM [".$db_name."].[dbo].[group])
					AS user_with_numbers
				WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT  id Value, name DisplayText,id,name FROM [".$db_name."].[dbo].[group] ";
	}
	
	$sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[group] "; 
	
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
function createGroupDataBase($parameters) {
    global $db, $db_table_prefix,$db_name;
    $name=$db->sql_escape($parameters['name']);
    $code=$db->sql_escape($parameters['code']);
    $area_code=$db->sql_escape($parameters['area_code']);
    $alias=$db->sql_escape($parameters['alias']);
    $flagcountry=$db->sql_escape($parameters['flagcountry']);

    $sql = "
    INSERT INTO [".$db_name."].[dbo].[country]
    	(
			name,code,area_code,alias,flagcountry
		)
	VALUES(
		'{$name}','{$code}','{$area_code}','{$alias}','{$flagcountry}'
		)";
			

    $result = $db->sql_query($sql);
	
	
    if ($result) 
    {
		$sentencia="SELECT id,name,code,area_code,alias,flagcountry FROM country WHERE id=@@IDENTITY";
		$data = $db->sql_query($sentencia);
		$data = $db->sql_fetchrow($data);
        return $data;
    }
    return false;
}

//You can update sites
function updateGroupDataBase($parameters) {
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
    	name='{$name}',
    	code='{$code}',
    	area_code='{$area_code}',
    	alias='{$alias}',
    	flagcountry='{$flagcountry}'
    WHERE
    	id={$id}
	";
    $result = $db->sql_query($sql);
    if($result){
    	$sql="SELECT id,name,code,area_code,alias,flagcountry FROM [{$db_name}].[dbo].[country] WHERE id=".$id;
    	$result = $db->sql_query($sql);
    	$result = $db->sql_fetchrow($sql);
    }
    return $result;
}


//You can delete sites
function deleteGroupDataBase($parameters) {
    global $db, $db_table_prefix, $db_name;
    $id=($parameters['id']);
    ///var/www/html/mcbisite/admin/service/models
    ///var/www/html/mcbisite/assets/images/countries

    $sql="SELECT flagcountry FROM [{$db_name}].[dbo].[country] WHERE id=".$id;
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrow($result);
    $file='/var/www/html/mcbisite/assets/images/countries/'.$result['flagcountry'];

    if(file_exists($file)){
    	unlink($file);error_log($file);
    }

    $sql = "DELETE FROM [{$db_name}].[dbo].[country] WHERE id={$id}";
    $result = $db->sql_query($sql);

    


    return ($result);
}

?>