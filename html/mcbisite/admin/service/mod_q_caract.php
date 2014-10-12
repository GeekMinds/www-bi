<?php
// Modulo de Ficha tecnica de producto.
    require_once("models/config.php");
    require_once("models/funcs.mod_q_caract.php");
    //header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);

switch ($action) {
	case 'update':
		$result = 'not implemented yet';
		break;
	case 'getcontent':
		$result = getMoudelProductoContent($parameters);
                break;
	case 'savecontent': 
		$result = createPageAndSaveContents($parameters);
                break;
	case 'delete':
		$result = delMoudelProductoContent($parameters);
		break;
	case 'notlogued':
		$result['Result'] = 'ERROR';
		$result['Message'] = 'Usuario no logueado';
		break;
	default:
		$result['Result'] = 'ERROR';
		$result['Message'] = 'Operación no definida';
                return $result;
}



function getMoudelProductoContent($parameters = array()){
    $result = queryModuleProductoDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}


function createPageAndSaveContents($parameters = array()){
    if($parameters['data']['content_id'] == '-1'){
        $result = createPageAndSaveContentsDB($parameters);
    }else{
        $result = updateProductDB($parameters);
    }
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}
