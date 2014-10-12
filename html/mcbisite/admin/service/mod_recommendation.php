<?php

require_once("./models/config.php");
require_once("./models/funcs.mod_recommendation.php");

header('Access-Control-Allow-Origin: *');


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listModuleRecommendation($parameters = array()) {
    //Get records from database
    $data = listModuleRecommendationDataBase($parameters);
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
function createModuleRecommendation($parameters = array()) {
	global $db;
    $result = createModuleRecommendationDataBase($parameters);
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
function getModuleRecommendation($parameters = array()) {
    //Get records from database
    global $db;
    $data = getModuleRecommendationDataBase($parameters);
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
function updateModuleRecommendation($parameters = array()) {
    //Update from database
    updateModuleRecommendationDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// save specific item
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function saveModuleRecommendation($parameters = array()) {
	
    //Update from database
    $return = saveModuleRecommendationDataBase($parameters);
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

//if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'list':
        $result = listModuleRecommendation($parameters);
        break;
    case 'get':
        $result = getModuleRecommendation($parameters);
        break;
    case 'create':
        $result = createModuleRecommendation($parameters);
        break;
    case 'update':
        $result = updateModuleRecommendation($parameters);
        break;
    case 'save'://create or update
        $result = saveModuleRecommendation($parameters);
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