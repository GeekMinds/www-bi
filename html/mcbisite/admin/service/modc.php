<?php

require_once("./models/config.php");
require_once("./models/funcs.modc.php");


header('Access-Control-Allow-Origin: *');

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// createModC
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createModC($parameters = array()) {
    $result = createModCDataBase($parameters);
    //Get last inserted record (to return to jTable)
   // $row = getLastInsertionModC();
    //Return result to jTable
    $jTableResult = array();
	if($result){
    	$jTableResult['Result'] = "OK";
    	$jTableResult['Record'] = $result;
	}else{
    	$jTableResult['Result'] = "ERROR";
    	$jTableResult['Message'] = "Ha ocurrido un error intente nuevamente más tarde.";
	}
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// listModC
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listModC($parameters = array()) {
    //Get records from database
    $data = listModCDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['Options'] = $data['rows'];
    $jTableResult['TotalRecordCount'] = $data["count"];

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// getMenu
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getMenu($parameters = array()) {
    //Get records from database
    global $db;
    $data = getMenuDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['Options'] = $data['rows'];
    $jTableResult['TotalRecordCount'] = $data["count"];


    $db->sql_close();
    return $jTableResult;
}
function deleteMenu($parameters = array()) {
    global $db;
    $result = array();
    $data = deleteModCDataBase($parameters['data']);
    if($data){
        $result['Result'] = "OK";
    }else{
        $result['Result'] = "ERROR";
    }
    $db->sql_close();
    return $result;
}
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// getSubMenu
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getSubMenu($parameters = array()) {
    //Get records from database
    global $db;
    $data = getSubMenuDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['Options'] = $data['rows'];
    //$jTableResult['TotalRecordCount'] = $data["count"];


    $db->sql_close();
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// getSubMenuChilds
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getSubMenuChilds($parameters = array()) {
    //Get records from database
    global $db;
    $data = getSubMenuChildsDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['Options'] = $data['rows'];
    //$jTableResult['TotalRecordCount'] = $data["count"];

    $db->sql_close();

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// saveSubMenuChilds
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function saveSubMenuChilds($parameters = array()) {
    //Get records from database
    global $db;
    $data = saveSubMenuChildsDataBase($parameters['data']);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['Options'] = $data['rows'];
    //$jTableResult['TotalRecordCount'] = $data["count"];

    $db->sql_close();

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// updateModC
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateModC($parameters = array()) {
	global $db;
    //Update from database
    $result = updateModCDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
	if($result){
    	$jTableResult['Result'] = "OK";
	}else{
		$jTableResult['Result'] = "ERROR";
		$jTableResult['Message'] = "Error en la base de datos";
	}
	$db->sql_close();
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// updateHeaderSequence
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateHeaderSequence($parameters = array()) {
	global $db;
    //Update from database
    $result = updateHeaderSequenceDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
	if($result){
    	$jTableResult['Result'] = "OK";
	}else{
		$jTableResult['Result'] = "ERROR";
		$jTableResult['Message'] = "Error en la base de datos";
	}
	$db->sql_close();
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// deteleModC
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deteleModC($parameters = array()) {
    //Delete from database
    $result = deleteModCDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}

function getModule($parameters = array()) {
    $data = getModuleDataBase($parameters);
    $result = array();
    $result['error'] = "OK";
    $result['result'] = $data;
    return $result;
}

function getModuleExternal($parameters = array()) {
    $data = getModuleExternalDataBase($parameters);
    $result = array();
    $result['error'] = "OK";
    $result['result'] = $data;
    return $result;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// listPages
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listPages($parameters = array()) {
    //Get records from database
    $data = listPagesDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['Options'] = $data['rows'];
    $jTableResult['TotalRecordCount'] = $data["count"];

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH PARAMETERS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);

error_log("POST PARAMETERS -----> ".json_encode($_POST));
$callback = getCallback($parameters);
$action = getAction($parameters);

if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'getmenu':
        $result = getMenu($parameters);
        break;
    case 'deletemenu':
        $result = deleteMenu($parameters);
        break;
    case 'getsubmenu':
        $result = getSubMenu($parameters);
        break;
    case 'getsubmenuchilds':
        $result = getSubMenuChilds($parameters);
        break;
    case 'savesubmenuchilds':
        $result = saveSubMenuChilds($parameters);
        break;
    case 'create':
        $result = createModC($parameters);
        break;
    case 'list':
        $result = listModC($parameters);
        break;
    case 'updateheadersequence':
        $result = updateHeaderSequence($parameters);
        break;
    case 'update':
        $result = updateModC($parameters);
        break;
    case 'delete':
        $result = deteleModC($parameters);
        break;
    case 'getmodulciframe':
        $result = getModule($parameters);
        break;
    case 'getmodulciframe_external':
        $result = getModuleExternal($parameters);
        break;
    case 'listpages':
        $result = listPages($parameters);
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