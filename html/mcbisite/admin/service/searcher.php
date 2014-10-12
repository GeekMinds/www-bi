<?php


//Includes
require_once("models/config.php");
require_once("models/funcs.searcher.php");
header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_GET,$_POST);
$callback = getCallback($parameters);
$action = getAction($parameters);

$result = array();

//Chequeando si el usuario esta autenticado
if(!isUserLoggedIn()) { $action = "notlogued";}

//Devolviendo resultado deacuerdo a la acción solicitada
switch ($action) {
    case 'create': 
        $result = createSearcher($parameters);
    break;
    
    case 'update':
        $result = updateSearcher($parameters);
    break;
    
    case 'getcontent':
       $result = getContentSearcher($parameters);
    break;

    case 'getcontentparameters':
        $result=getContentParameters($parameters);
        break;

    case 'parameterstype':
        $result=getParametersType($parameters);
        break;

    case 'createparameters':
        $result=createParameters($parameters);
        break;
    case 'updateparameters':
        $result=updateParameters($parameters);
        break;

    case 'deleteparameters':
        $result=deleteParameters($parameters);
        break;

    /*
    case 'delete':
        $result = 'not implemented yet';
        break;
    case 'approval':
        $result = 'not implemented yet';
        break;
    case 'disapproval':
        $result = 'not implemented yet';
        break;*/
    case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
        break;
    
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}



/*
{DATA:"ERE"}
*/

//Declaración de funciones

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Creación de módulo
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createSearcher($parameters = array()){
    //var_dump($parameters);
    $result = createSearcherDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $result;
    
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtener módulo
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getContentSearcher($parameters = array()){
	$result = getContentSearcherDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";//OK O bien ERROR
    $jTableResult['Record'] = $result;
    //var_dump($jTableResult);
    return $jTableResult;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Actualización de módulo
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateSearcher($parameters = array()){
    $result = updateSearcherDataBase($parameters);
    return $result;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Crear Parametros
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createParameters($parameters = array()){
    $result = createParametersDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene Parametros
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getContentParameters($parameters= array()){
    $result=getContentParametersDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene Tipos Parametros
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getParametersType($parameters=array()){
    $result=getParametersTypeDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Options'] = $result; 
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Actualiza Parametros
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateParameters($parameters=array()){
    $result=updateParametersDataBase($parameters);
    $jTableResult=array();
    if($result.error==0){
        $jTableResult['Result'] = "OK";
    }else{
        $jTableResult['Result'] = "ERROR";
    }
    
    $jTableResult['Records'] = $result; 
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Elimina Parametros
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deleteParameters($parameters=array()){
    $result=deleteParametersDataBase($parameters);
    $jTableResult=array();
    if($result.error==0){
        $jTableResult['Result'] = "OK";
    }else{
        $jTableResult['Result'] = "ERROR";
    }
    
    $jTableResult['Records'] = $result; 
    return $jTableResult;
}
/*
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Eliminación de módulo
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deleteSearcher($parameters = array()){

}
*/

echo json_encode($result);

?>