<?php

require_once("./models/config.php");
require_once("./models/funcs.mod_k.php");

header('Access-Control-Allow-Origin: *');


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listModuleK($parameters = array()) {
    //Get records from database
    $data = listModuleKDataBase($parameters);
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
// create
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createModuleK($parameters = array()) {
	global $db;
    $result = createModuleKDataBase($parameters);
    //Get last inserted record (to return to jTable)
    $row = getLastInsertion();
    //Return result to jTable
    $jTableResult = array();
	if($result){
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $row;
	}else{
		$jTableResult['Result'] = "ERROR";
		$jTableResult['Record'] = "Error en la base de datos";
	}
	$db->sql_close();
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// get specific item
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getModuleK($parameters = array()) {
    //Get records from database
    global $db;
    $data = getModuleKDataBase($parameters);
    //Return result to jTable
    $result = array();
    $result['Result'] = "OK";
    $result['Records'] = $data;

    $db->sql_close();
    return $result;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// update specific item
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateModuleK($parameters = array()) {
    //Update from database
    updateModuleKDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// save specific item
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function saveModuleK($parameters = array()) {
	
    //Update from database
    $return = saveModuleKDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
	
	if($return){
		$jTableResult['Result'] = "OK";
		$jTableResult['Record'] = $return;
	}else{
		$jTableResult['Result'] = "ERROR";
		$jTableResult['Message'] = "Error en la base de datos no se puedo guardar";
	}
    //return $jTableResult;
        return $return;
}

/*********************************************************************************************************
**	ADMIN QUESTIONS
***********************************************************************************************************/



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all products
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listProduct($parameters = array()) {
	global $db, $db_table_prefix, $db_name, $site_id;
    //Get records from database
    $data = listProductDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
	if($data){
		$jTableResult['Result'] = "OK";
		$jTableResult['Records'] = $data['rows'];
		$jTableResult['Data'] = $data['data'];
		$jTableResult['TotalRecordCount'] = $data["count"];
	}else{
		$jTableResult['Result'] = "ERROR";
		$jTableResult['Message'] = "Error en la base de datos.";
	}
	$db->sql_close();
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// create product
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function create($parameters = array()) {
	global $db;
    $result = createDataBase($parameters);
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
// update specific product
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function update($parameters = array()) {
    //Update from database
    updateDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// delete specific product
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function delete($parameters = array()) {
    //Update from database
    deleteDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
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
        $result = listModuleK($parameters);
        break;
    case 'get':
        $result = getModuleK($parameters);
        break;
    case 'create':
        $result = createModuleK($parameters);
        break;
    case 'update':
        $result = updateModuleK($parameters);
        break;
    case 'save'://create or update
        $result = saveModuleK($parameters);
        break;
    case 'listproducts':
        $result = listProduct($parameters);
        break;
    case 'create_product':
        $result = create($parameters);
        break;
    case 'update_product':
        $result = update($parameters);
        break;
    case 'delete_product':
        $result = delete($parameters);
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