<?php
//**FORMULARIOS**//

require_once("./models/config.php");
require_once("./models/funcs.module_form.php");


header('Access-Control-Allow-Origin: *');

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// createModForm
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createModForm($parameters = array()) {
    
    $result = createModFormDataBase($parameters['data']);

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['module_id'] = $result;
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// getForm
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getForm($parameters = array()) {
    
    $result = getFormDataBase($parameters['data']);

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['result'] = $result;
    return $jTableResult;
}
function getOptions($parameters = array())
{
   $result= getOptionsDataBase($parameters['data']);
   $jTableResult = array();
   $jTableResult['Result'] = "OK";
   $jTableResult['result'] = $result;
   return $jTableResult;
    
    
}

function getCRM($parameters = array()){
    $result = loadXML($parameters);
    $jTableResult['Result'] = "OK";
    $jTableResult['codigo'] = $parameters['codigo'];
    $jTableResult['result'] = $result['r'];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH PARAMETERS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);

//error_log("POST PARAMETERS -----> ".json_encode($_POST));
$callback = getCallback($parameters);
$action = getAction($parameters);

if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'createform':
        $result = createModForm($parameters);
        error_log('RESULT ----- >  ' .json_encode($result));
        break;
    case 'getform':
        $result = getForm($parameters);
        break;
    case 'getOptions':
        $result = getOptions($parameters);
        break;
    case 'getCrm':
        $result = getCRM($parameters);
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