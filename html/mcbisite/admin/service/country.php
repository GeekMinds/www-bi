<?php

require_once("./models/config.php");
require_once("./models/funcs.country.php");

header('Access-Control-Allow-Origin: *');


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listCountry($parameters = array()) {
    //Get records from database
    $data = listCountryDataBase($parameters);
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
function listCountryDropDown($parameters = array()) {
    //Get records from database
    $data = listCountryDataBaseDropDown($parameters);
    //Return result to jTable
    $jTableResult = array();
	
	if($data){
		$jTableResult['Result'] = "OK";
		$jTableResult['Data'] = $data;
	}else{
		$jTableResult['Result'] = "ERROR";
		$jTableResult['Message'] = "Error inesperado en la base de datos.";
	}

    return $jTableResult;
}
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getCountry($parameters=array()){
	 $jTableResult = array();
	 $jTableResult['Result'] = "ERROR";
	$jTableResult['Message'] = "Función no definida.";
	return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// create site
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createCountry($parameters = array()) {
	global $db;
    $result = createCountryDataBase($parameters);
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
function updateCountry($parameters = array()) {
    //Update from database
    $data=updateCountryDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $data;

    return $jTableResult;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// delete specific question
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deleteCountry($parameters = array()) {
    //Update from database
    deleteCountryDataBase($parameters);
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
        $result = listCountry($parameters);
        break;
    case 'Droplist':
        $result = listCountryDropDown($parameters);
        break;
    case 'get':
        $result = getCountry($parameters);
        break;
    case 'create':
        $result = createCountry($parameters);
        break;
    case 'update':
        $result = updateCountry($parameters);
        break;
    case 'delete':
        $result = deleteCountry($parameters);
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