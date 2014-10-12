<?php
/*


*/

require_once("./models/config.php");
require_once("./models/funcs.module_m.php");
header('Access-Control-Allow-Origin: *');


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// readModuleMedia
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function readModuleMedia($parameters) {
    
    //$result = readModuleMediaDataBase($parameters['data']);
    $result = readModuleMediaDataBase($parameters);

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['media'] = $result;
   // error_log("*****Entrando a module_m.php");
    //$jTableResult['Records'] = $result;
    return $jTableResult;
}

function obtainImages($parameters = array()) {
    
    //$result = readModuleMediaDataBase($parameters['data']);
    $result = obtainImagesDB($parameters);

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    //$jTableResult['media'] = $result;
    $jTableResult['Records'] = $result['Records'];
    $jTableResult['TotalRecordCount'] = $result['RecordCount'];
    return $jTableResult;
}


function addPhoto($parameters = array()) {

    $result =addModuleMediaPhotoDataBase($parameters);
    
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result[0];
    //dumpear('Dumpenado lo que se va a mandar',$jTableResult);
    return $jTableResult;
}
function updateMedia($parameters = array()) {

    $result =updateMediaDB($parameters);
    
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result[0];

    return $jTableResult;
}

function deletePhoto($parameters = array()) {

    $result =deletePhotoDB($parameters);
    
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";

    //dumpear('Dumpenado lo que se va a mandar',$jTableResult);
    return $jTableResult;
}




//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH THE CHIWI
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);

error_log("POST PARAMETERS -----> ".json_encode($_POST));
$callback = getCallback($parameters);
$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}

error_log('ACTION -- > ' . $action );
switch ($action) {
    case 'readmodulemedia':

        $result = readModuleMedia($parameters);
    break;

    case 'addphoto':
        $result=addPhoto($parameters);
    break;
    case 'updatemedia':
        $result=updateMedia($parameters);
    break;
    case 'deletephoto':
        $result=deletePhoto($parameters);
    break;
    case 'obtainimages':
        $result=obtainImages($parameters);
    break;
    case 'getalltags':
        $result=getAllTagsDB($parameters);
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