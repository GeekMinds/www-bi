<?php

require_once("models/config.php");

require_once("models/funcs.page.php");

header('Access-Control-Allow-Origin: *');

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// ListTransitions
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function listPages() {
	 global $db, $db_name;
    //Get records from database
    $data = listPagesDataBase();
    //Return result to jTable
    $jTableResult = array();

	if($data){
		$jTableResult['Result'] = "OK";
		$jTableResult['Records'] = $data;
	}else{
		$jTableResult['Result'] = "ERROR";
	}

	$db->sql_close();
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// createCarrousel
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function createCarrousel($parameters = array()){
    
    $result = createCarrouselDataBase($parameters['data']);

    $jTableResult = array();

    $jTableResult['Result'] = "OK";

    $jTableResult['Record'] = $result;

    return $jTableResult;
}

$parameters = getParameters($_POST, $_GET);


$callback = getCallback($parameters);

$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'list':
        $result = listPages($parameters);
        break;
    
    case 'createcarrousel':
        $result = createCarrousel($parameters);
        break;

    case 'update':
        $result = 'not implemented yet';
        break;

    case 'delete':
        $result = 'not implemented yet';
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
