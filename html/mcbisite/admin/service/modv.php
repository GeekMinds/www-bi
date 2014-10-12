<?php
//**FORMULARIOS**//

require_once("./models/config.php");
require_once("./models/funcs.module_form.php");


header('Access-Control-Allow-Origin: *');

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// createModV
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createModForm($parameters = array()) {
    
    $result = createModFormDataBase($parameters['data']);

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $row;
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
    case 'createform':
        $result = createModForm($parameters);
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