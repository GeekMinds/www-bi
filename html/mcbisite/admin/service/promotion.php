<?php


//Includes
require_once("models/config.php");
require_once("models/funcs.promotion.php");
header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_GET,$_POST);
$callback = getCallback($parameters);
$action = getAction($parameters);
//var_dump($parameters);
$result = array();

//Chequeando si el usuario esta autenticado
if(!isUserLoggedIn()) { $action = "notlogued";}

//Devolviendo resultado deacuerdo a la acción solicitada
switch ($action) {
   
    case 'getpromotions':
        $result=getPromotions($parameters);
        break;

    case 'parameterstype':
        $result=getPromotionsType($parameters);
        break;

    case 'createpromotions':
        $result=createPromotions($parameters);
        break;
    case 'updatepromotions':
        $result=updatePromotions($parameters);
        break;

    case 'deletepromotions':
        $result=deletePromotions($parameters);
        break;

    case 'getalltags':
        $result=getAllTagsDataBase($parameters);
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
// Crear Parametros
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createPromotions($parameters = array()){
    $result = createPromotionsDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene Parametros
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getPromotions($parameters= array()){
    $result=getPromotionsDataBase($parameters);
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
function updatePromotions($parameters=array()){
    $result=updatePromotionsDataBase($parameters);
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
function deletePromotions($parameters=array()){
    $result=deletePromotionsDataBase($parameters);
    return $result;
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