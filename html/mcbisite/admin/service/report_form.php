<?php

//Includes
require_once("models/config.php");
require_once("models/funcs.report_form.php");
header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_GET,$_POST);
$callback = getCallback($parameters);
$action = getAction($parameters);
$result = array();

//Chequeando si el usuario esta autenticado
//if(!isUserLoggedIn()) { $action = "notlogued";}

//Devolviendo resultado deacuerdo a la acción solicitada
switch ($action) {
//Admin
    case 'createcarruselhistory':
        $result=createCarruselHistory($parameters);
        break;

    case 'getform':
        $result = getFormDataBase($parameters);
        break;

    case 'getpage':
        $result = getPageDataBase($parameters);
        break;

    case 'getcarrusel':
        $result = getCarruselDataBase($parameters);
        //$result["la"]=$parameters;
        break;

    case 'listform':
        $result=listForm($parameters);
        break;

    case 'list_detail':
        $result = listDetailApproval($parameters);
        break;

//No logueado
    case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
        break;
    
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}

//Admin

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Crea datos de historial de Usuarios de Administración
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createCarruselHistory($parameters = array()){
    $result = createCarruselHistoryDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene Historial de Usuarios de Administración
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listForm($parameters= array()){
    $result=listFormDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    return $jTableResult;
}

function listDetailApproval($parameters = array()) {
    //Get records from database
    $result = listApprovalDetailDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    
    if($result){
        $jTableResult['Result'] = "OK";
        $jTableResult['Records'] = $result['rows'];
        $jTableResult['Options'] = $result['rows'];
        $jTableResult['TotalRecordCount'] = $result["count"];
        //$jTableResult['sql']=$result['sql'];
    }else{
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "Error inesperado en la base de datos.";
    }

    return $jTableResult;
}


//User



echo json_encode($result);

?>