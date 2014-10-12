<?php

require_once("./models/config.php");
require_once("./models/funcs.mod_a.php");


header('Access-Control-Allow-Origin: *');

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// createModA
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createModA($parameters = array()) {
    $result = createModADataBase($parameters);
    //Get last inserted record (to return to jTable)
    $row = getLastInsertionModA();
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $row;
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// listModA
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listModA($parameters = array()) {
    //Get records from database
    $data = listModADataBase($parameters);
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
function getModuleA($parameters = array()) {
    //Get records from database
    global $db;
    $data = getModuleADataBase($parameters);
    //Return result to jTable
    $result = array();
    $result['error'] = "0";
    $result['result'] = $data;

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
// updateModA
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateModA($parameters = array()) {
    //Update from database
    updateModADataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// updateItemModA
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateItemModA($parameters = array()) {
	 global $db, $db_table_prefix;
    //Update from database
    $return = updateItemModADataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
	
	if( $return){
    	$jTableResult['Result'] = "OK";
	}else{
    	$jTableResult['Result'] = "ERROR";
    	$jTableResult['Return'] = $return;
	}
	
    $db->sql_close();
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// deteleModA
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deteleModA($parameters = array()) {
    //Delete from database
    $result = deleteModADataBase($parameters);
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

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH PARAMETERS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);

error_log("POST PARAMETERS -----> ".json_encode($_POST));
$callback = getCallback($parameters);
$action = getAction($parameters);

if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'getModuleA':
        $result = getModuleA($parameters);
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
        $result = createModA($parameters);
        break;
    case 'list':
        $result = listModA($parameters);
        break;
    case 'update':
        $result = updateModA($parameters);
        break;
    case 'update_item':
        $result = updateItemModA($parameters);
        break;
    case 'delete':
        $result = deteleModA($parameters);
        break;
    case 'getmodulciframe':
        $result = getModule($parameters);
        break;
    case 'getmodulciframe_external':
        $result = getModuleExternal($parameters);
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