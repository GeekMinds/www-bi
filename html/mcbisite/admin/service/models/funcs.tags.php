<?php
header('Access-Control-Allow-Origin: *');

function addTagDB($parameters){
	global $db, $db_table_prefix;
	$tag=$db->sql_escape($parameters['tag']);
	$sql = " INSERT INTO {$db_name}.dbo.tag(tag) VALUES('{$tag}')";
	$db->sql_query($sql);

	global $db, $db_table_prefix;
	$sql2 = "SELECT id,tag FROM {$db_name}.dbo.tag  WHERE id=@@IDENTITY";
	$result= $db->sql_query($sql2);
	$result= $db->sql_fetchrow($result);									
	return $result;
}

function verificarTagDB($parameters){
	global $db, $db_table_prefix;
	$tag=$db->sql_escape($parameters['tag']);
	$sql = " SELECT id FROM {$db_name}.dbo.tag WHERE tag='{$tag}'";
	$result=$db->sql_query($sql);
	$result= $db->sql_fetchrow($result);
	$id=trim(strval($result['id']));

	if(strlen($id)>0)
		return true;
	else
		return false;
}








function updateTagDB($parameters){
	global $db, $db_table_prefix;
	$tag=$db->sql_escape($parameters['tag']);
	$id=($parameters['id']);
	$sql = " UPDATE {$db_name}.dbo.tag SET tag='{$tag}' WHERE id={$id}";
	$db->sql_query($sql);

	global $db, $db_table_prefix;
	$sql2 = "SELECT id,tag FROM {$db_name}.dbo.tag  WHERE id={$id}";
	$result= $db->sql_query($sql2);
	$result= $db->sql_fetchrow($result);									
	return $result;
	

}








function deleteTagDB($parameters){
	global $db, $db_table_prefix;
	$id=$parameters['id'];
  	$sql="DELETE FROM {$db_name}.dbo.tag WHERE id=".$id;
  	$result=$db->sql_query($sql);
	return $result;
}


function getTagsDB($parameters=array()){
	global $db, $db_table_prefix,$db_name;;
	$data = array();
	$parameters['type'] = (isset($parameters['type'])) ? $parameters['type'] : '';
	$parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'id DESC ';
	$parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';	
	$sql = "";
	$sql_size = "";
	if($parameters['jtpagesize'] != ''){
		$parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
		$sql = "SELECT * FROM
					(SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id,tag FROM [".$db_name."].[dbo].[tag])
					AS user_with_numbers
				WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
	}else{
		$sql = "SELECT id,tag FROM [".$db_name."].[dbo].[tag] ";
	}
	
	$sql_count = "SELECT COUNT(*) total FROM [".$db_name."].[dbo].[tag] ";
	
	$result = $db->sql_query($sql);
	$result_count = $db->sql_query($sql_count);

	$result= $db->sql_fetchrowset($result);

	

	$result_count= $db->sql_fetchrow($result_count);
	$data["result"] = $result;
	$data["count"] = $result_count['total'];
	
	return $data;
}





?>