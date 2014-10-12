<?php 
//echo 1;
//echo json_encode('ok');
////return 1;
//die();
require_once("models/config.php");
require_once("models/funcs.mod_s1.php");
header('Access-Control-Allow-Origin: *');
//$data = json_decode($_POST['myJson'], true);

//return false;
//die();
$parameters = json_decode($_POST['pregunta'], true);


$callback = getCallback($parameters);
$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}
//echo json_encode($action);
//die();

//die();
    switch ($action) 
    {
        case 'agregarPregunta':
           
                $result = guardarPregunta($parameters);
//                $result['Result'] = 'ok';
//                $result['Message'] = 'Usuario logueado';
                //echo json_encode('ok');
                break;
        case 'agregarModS1':
                $result = guardarModS1($parameters);
            break;
        case 'borrarPregunta':
                $result = borrarPregunta($parameters);
            break;
        case 'notlogued':
                $result['Result'] = 'ERROR';
                $result['Message'] = 'Usuario no logueado';
        break;
        default:
        $result['Result'] = $parameters;
        $result['Message'] = 'Operación no definida';
    }


if (strlen($callback) > 0) {
  echo $callback . '(' . json_encode($result) . ');';
} else {
  echo json_encode($result);
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Change status to Question
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function borrarPregunta($parameters = array()){
        global $db;
	$data = guardarEstado($parameters);
	//Return result to jTable
	$jTableResult = array();
	$jTableResult['error'] = "0";
	$jTableResult['result'] = $data;
	
	$db->sql_close();
	return $jTableResult;
}
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Create new Question with/without answers
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function guardarPregunta($parameters = array()){
//Get records from database
	global $db;
	$data = guardarPreguntaDB($parameters);
	//Return result to jTable
	$jTableResult = array();
	$jTableResult['error'] = "0";
	$jTableResult['result'] = $data;
	
	$db->sql_close();
	return $jTableResult;
	
}

function guardarModS1($parameters = array()){
    global $db;
	$data = guardarModS1DB($parameters);
	//Return result to jTable
	$jTableResult = array();
	$jTableResult['error'] = "0";
	$jTableResult['result'] = $data;
	
	$db->sql_close();
	return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH PARAMETERS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\


?>