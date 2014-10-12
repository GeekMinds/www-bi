<?php

require_once("./models/config.php");
require_once("./models/funcs.approval.php");

header('Access-Control-Allow-Origin: *');


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listApproval($parameters = array()) {
    //Get records from database
    $data = listApprovalDataBase($parameters);
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
function listApprovalForma($parameters = array()) {
    //Get records from database
    $data = listApprovalDataBaseForma($parameters);
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
function listDetailApproval($parameters = array()) {
    //Get records from database
    $data = listApprovalDetailDataBase($parameters);
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
function getApproval($parameters=array()){
	 $jTableResult = array();
	 $jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = "Función no definida.";
	return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// create site
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createApproval($parameters = array()) {
	global $db;
    $result = createApprovalDataBase($parameters);
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
function updateApproval($parameters = array()) {
    //Update from database
    $data=updateApprovalDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $data;

    return $jTableResult;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// delete specific question
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deleteApproval($parameters = array()) {
    //Update from database
    //deleteApprovalDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    return $jTableResult;
}



function callApproveChange($parameters=array()){
	for($i=0; $i<sizeof($parameters["rows"]); $i++){
		$approval = $parameters["rows"][$i];
		approveChange($approval);
	}
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Message'] = "La aprobacíon fue realizada";
    return $jTableResult;
}
function callApproveChangeForma($parameters=array()){
	for($i=0; $i<sizeof($parameters["rows"]); $i++){
		$approval = $parameters["rows"][$i];
		approveChangeForma($approval);
	}
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Message'] = "La aprobacíon fue realizada";
    return $jTableResult;
}



function callDisapproveNotDiscard($parameters=array()){
	for($i=0; $i<sizeof($parameters["rows"]); $i++){
		$approval = $parameters["rows"][$i];
		disapproveNotDiscard($approval);
	}
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Message'] = "Se ha enviado una notificación para que el cambio sea revisado nuevamente.";
    return $jTableResult;
}


function callDisapproveDiscard($parameters=array()){
	for($i=0; $i<sizeof($parameters["rows"]); $i++){
		$approval = $parameters["rows"][$i];
		disapproveDiscard($approval);
	}
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Message'] = "El cambio fue descartado.";
    return $jTableResult;
}
function callDisapproveDiscardForma($parameters=array()){
	for($i=0; $i<sizeof($parameters["rows"]); $i++){
		$approval = $parameters["rows"][$i];
		disapproveDiscardForma($approval);
	}
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Message'] = "El cambio fue descartado.";
    return $jTableResult;
}

function setModeSite($parameters=array()){
	$parameters['mode'] = (isset($parameters['mode'])) ? $parameters['mode'] : 'public';
	$parameters["edit"] = "";
	
	if($parameters['mode']=="edition"){
		$parameters["edit"] = "_edit";
	}
	
	$_SESSION["edit"] = $parameters["edit"];
	$mode = "aún no aprobado";
	if($parameters["edit"]==""){
		$mode = "publicado";
	}
	
    $jTableResult['Result'] = "OK";
    $jTableResult['Message'] = "Usted estará viendo el sitio ".$mode.".";
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
        $result = listApproval($parameters);
        break;
    case 'list_forma':
        $result = listApprovalForma($parameters);
        break;
    case 'list_detail':
        $result = listDetailApproval($parameters);
        break;
    case 'get':
        $result = getApproval($parameters);
        break;
    case 'create':
        $result = createApproval($parameters);
        break;
    case 'update':
        $result = updateApproval($parameters);
        break;
    case 'delete':
        $result = deleteApproval($parameters);
        break;
    case 'approve':
        $result = callApproveChange($parameters);
        break;
    case 'approve_forma':
        $result = callApproveChangeForma($parameters);
        break;	
    case 'notdiscard':
        $result = callDisapproveNotDiscard($parameters);
        break;
    case 'notdiscard_forma':
        $result = callDisapproveNotDiscard($parameters);
        break;
    case 'discard':
        $result = callDisapproveDiscard($parameters);
        break;
    case 'discard_forma':
        $result = callDisapproveDiscardForma($parameters);
        break;
    case 'sitemode':
        $result = setModeSite($parameters);
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