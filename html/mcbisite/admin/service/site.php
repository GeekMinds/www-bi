<?php

require_once("./models/config.php");
require_once("./models/funcs.site.php");

header('Access-Control-Allow-Origin: *');


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listSite($parameters = array()) {
    //Get records from database
    $data = listSiteDataBase($parameters);
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
function getSite($parameters=array()){
	 $jTableResult = array();
	 $jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = "Función no definida.";
	return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// create site
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createSite($parameters = array()) {
	global $db;
    $result = createSiteDataBase($parameters);
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
function updateSite($parameters = array()) {
    //Update from database
    updateSiteDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// delete specific question
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deleteSite($parameters = array()) {
    //Update from database


    $result = deleteSiteDataBase($parameters);
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


function infoSite($parameters){

        //Get records from database
    $data = infoSiteDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    
    if($data){
        $jTableResult['Result'] = "OK";
        $jTableResult['Records'] = $data['rows'];
        
        
    }else{
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "Error inesperado en la base de datos.";
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
        $result = listSite($parameters);
        break;
    case 'get':
        $result = getSite($parameters);
        break;
    case 'create':
        $result = createSite($parameters);
        break;
    case 'update':
        $result = updateSite($parameters);
        break;
    case 'delete':

        $result = deleteSite($parameters);
        break;	
	case 'htacces':
		//$result = updateHtaccess();
		break;
    case 'validatealias':
        $result = validateAlias($parameters);
        break;
	case 'synchronizecorporationmenu':
		 $result = synchronizeCorporationMenu($parameters);
	break;

    case 'info_site':
         $result =infoSite($parameters);
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