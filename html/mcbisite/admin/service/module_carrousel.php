<?php
/*


*/

require_once("./models/config.php");
require_once("./models/funcs.module_carrousel.php");
header('Access-Control-Allow-Origin: *');


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// readModuleMedia
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function readModuleCarrousel($parameters) {
    
    //$result = readModuleCarrouselDataBase($parameters['data']);
    $result = readModuleCarrouselDataBase($parameters);

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
    $result = obtainCarrouselImagesDB($parameters);

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    //$jTableResult['media'] = $result;
    $jTableResult['Records'] = $result;
    return $jTableResult;
}


function addPhoto($parameters = array()) {

    $result =addModuleCarrouselPhotoDataBase($parameters);
    
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result[0];
    return $jTableResult;
}


function deletePhoto($parameters = array()) {

    $result =deleteCarrouselPhotoDB($parameters);
    
    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";

    return $jTableResult;
}

function updateMedia($parameters = array()) {

    $result = updateCarrouselDB($parameters);
    
    //Return result to jTable
   	$jTableResult = array();
    $jTableResult['Result'] = "OK";
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

        $result = readModuleCarrousel($parameters);
    break;

    case 'addphoto':
        $result=addPhoto($parameters);
    break;
	 case 'getalltags':
        $result=getAllCarrouselTagsDB($parameters);
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