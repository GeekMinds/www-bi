<?php


require_once("models/config.php");

require_once("models/funcs.module_issuu.php");

header('Access-Control-Allow-Origin: *');

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// saveMoudelTextContent
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\

function getApiKey($parameters = array()){
    
    $result = getApiKeyDataBase($parameters['data']);

    $jTableResult = array();

    $jTableResult['Result'] = "OK";

    $jTableResult['response'] = $result;

    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// getMoudelTextContent
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getMoudelTextContent($parameters=array()){
    //Get records from database
    global $db;
    $data = getMoudelTextContentDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['error'] = "0";
    $jTableResult['result'] = $data;
    
    $db->sql_close();
    return $jTableResult;
}



function createModuleIssuu($parameters = array()){
    
    $result = createModuleIssuuDataBase($parameters);

    $jTableResult = array();

    $jTableResult['Result'] = "OK";

    $jTableResult['response'] = $result;

    return $jTableResult;
}



$parameters = getParameters($_POST, $_GET);

error_log(json_encode($parameters));

$callback = getCallback($parameters);

$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {


    case 'createissuu':
        $result = createModuleIssuu($parameters);
    break;

    case 'update':
        $result = 'not implemented yet';
        break;

    case 'getapikey':
        $result = getApiKey($parameters);
    break;

    case 'savecontent': 
        $result = saveMoudelTextContent($parameters);
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
