<?php

require_once("./models/config.php");
require_once("./models/funcs.permission.php");

header('Access-Control-Allow-Origin: *');


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listPermission($parameters = array()) {
    //Get records from database
    $data = listPermissionDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
	
	if($data){
		$jTableResult['Result'] = "OK";
		$jTableResult['Records'] = $data['rows'];
		$jTableResult['Options'] = $data['rows'];
		$jTableResult['TotalRecordCount'] = $data["count"];
	}else{
		$jTableResult['Result'] = "ERROR";
		$jTableResult['Message'] = "Error inesperado en la base de datos.";
	}
	
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getPermission($parameters=array()){
	 $jTableResult = array();
	 $jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = "Función no definida.";
	return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// create permission
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createPermission($parameters = array()) {
	global $db;
    $result = createPermissionDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
	if($result){
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $result;
	}else{
		$jTableResult['Result'] = "ERROR";
		$jTableResult['Record'] = "Error en la base de datos";
	}
	$db->sql_close();
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// update specific question
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updatePermission($parameters = array()) {
    //Update from database
    updatePermissionDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// delete specific question
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deletePermission($parameters = array()) {
    //Update from database
    $result = deletePermissionDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
	if($result){
    	$jTableResult['Result'] = "OK";
	}else{
    	$jTableResult['Result'] = "ERROR";
    	$jTableResult['Message'] = "No se ha podido realizar la operación solicitada.";
	}
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH PARAMETERS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);

$callback = getCallback($parameters);
$action = getAction($parameters);

if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'list':
        $result = listPermission($parameters);
        break;
    case 'get':
        $result = getPermission($parameters);
        break;
    case 'create':
        $result = createPermission($parameters);
        break;
    case 'update':
        $result = updatePermission($parameters);
        break;
    case 'delete':
        $result = deletePermission($parameters);
        break;	
    case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
        break;
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}

if (strlen($callback) > 0) {
    echo $callback . '(' . json_encode($result) . ');';
} else {
    echo json_encode($result);
}
?>