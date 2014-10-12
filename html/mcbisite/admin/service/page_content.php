<?php

require_once("models/config.php");

require_once("models/funcs.page_content.php");

header('Access-Control-Allow-Origin: *');

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// createModB
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function deletePage($parameters = array()) { 
    global $db;
    $result = deleteAllContentFromPage($parameters['data']);
    //Get last inserted record (to return to jTable)
//    $row = getLastInsertionModB();
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
//    $jTableResult['Record'] = $row;
    $db->sql_close();
    return $result;
}
function createPage($parameters = array()) {
    global $db;
    $result = createPageDataBase($parameters['data']);
    //Get last inserted record (to return to jTable)
//    $row = getLastInsertionModB();
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
//    $jTableResult['Record'] = $row;
    $db->sql_close();
    return $result;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// listModB
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function listModB($parameters = array()) {
    //Get records from database
    $data = getModuleList();
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['Options'] = $data['rows'];
    $jTableResult['TotalRecordCount'] = $data["count"];
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// moduleName
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function moduleName($parameters = array()) {
	global $db;
	
    $result = moduleNameDataBase($parameters);
	
	//print_r($result);
	$db->sql_close();
    return $result;
}




//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// getButtons
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function getButtons($parameters = array()) {
    //Get records from database
    $data = getButtonsDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data['rows'];
    $jTableResult['Options'] = $data['rows'];
    $jTableResult['TotalRecordCount'] = $data["count"];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// updateModB
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function updateModB($parameters = array()) {
    //Update from database
    updateModBDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// deteleModB
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function deteleModB($parameters = array()) {
    //Delete from database
    $result = deleteModBDataBase($parameters);
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
    case 'getbuttons':
        $result = getButtons($parameters);
        break;

    case 'createpage':
        $result = createPage($parameters);
        break;
    case 'deletePage':
        $result = deletePage($parameters);
        break;

    case 'list':
        $result = listModB($parameters);
        break;
		
	
    case 'module_name':
        $result = moduleName($parameters);
        break;	

    case 'update':
        $result = updateModB($parameters);
        break;

    case 'delete':
        $result = deteleModB($parameters);
        break;
    
    case 'extLink':
        $result = extLink($parameters);
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