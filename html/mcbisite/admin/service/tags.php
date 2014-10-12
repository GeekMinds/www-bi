<?php
require_once("models/config.php");
require_once("models/funcs.tags.php");
header('Access-Control-Allow-Origin: *');
/*
	Parametros a ser utilizados
*/
$result=null;
$action=$_GET['action'];
$parameters = getParameters($_POST, $_GET);

function addTag($parameters){
	global $db;
	$result  = array();
	if(!verificarTagDB($parameters))
	{
		$data = addTagDB($parameters);
		if($data){
			$result["Result"] = "OK";
			$result["Record"] = $data;
			$result["Message"] = "OKAY";
		}else{
			$result["Result"] = "ERROR";
			$result["Message"] = "Error en la Base de datos";
		}
		$db->sql_close();
	}else{
		$result["Result"] = "ERROR";
		$result["Message"] = "El tag ya existe";

	}
	return $result;
}


function getTags($parameters=array()){
	global $db,$db_host;
	$result  = array();
	error_log($db_host);
	$data = getTagsDB($parameters);
	$result["Result"] = "OK";
	//dumpear($data);
	//if($data){
		
		$result["Records"] = $data['result'];
		$result["Message"] = "OK";
		$result['TotalRecordCount'] = $data['count'];
	//}else{
	//	$result["Result"] = "ERROR";
	//	$result["Message"] = "Error en la Base de datos";
	//}
	$db->sql_close();
	return $result;

}


function deleteTag($parameters){
	global $db;
	$result  = array();
	$data=deleteTagDB($parameters);
	if($data){
		$result["Result"] = "OK";
	}
	else{

		$result["Result"] = "ERROR";
		$result["Message"] = "Este Tag se encuentra en uso";
	}
	$db->sql_close();
	return $result;

}

function updateTag($parameters){
	global $db;
	$result  = array();
	$data = updateTagDB($parameters);
	if($data){
		$result["Result"] = "OK";
		$result["Record"] = $data;
		$result["Message"] = "OKAY";
	}else{
		$result["Result"] = "ERROR";
		$result["Message"] = "Error en la Base de datos";
	}
	$db->sql_close();
	return $result;

}


if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
	case 'create':
		$result=addTag($parameters);
		break;
	case 'delete':
		$result=deleteTag($parameters);
		break;
	case 'list':
		$result=getTags($parameters);
		break;
	case 'getalltags':
		$result=getAllTagsDB($parameters);
		break;
	case 'update':
		$result=updateTag($parameters);
		break;
	
}

echo json_encode($result);
?>