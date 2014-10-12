<?php

//Includes
require_once("models/config.php");
require_once("models/funcs.user_history.php");
header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_GET,$_POST);
$callback = getCallback($parameters);
$action = getAction($parameters);
$result = array();

//Chequeando si el usuario esta autenticado
if(!isUserLoggedIn()) { $action = "notlogued";}

//Devolviendo resultado deacuerdo a la acción solicitada
switch ($action) {
//Admin
   case 'getadminhistory':
        $result=getAdminHistory($parameters);
        break;

    case 'createadminhistory':
        $result=createAdminHistory($parameters);
        break;

    case 'getadmin':
        $result = getAdminDataBase($parameters);
        break;

    case 'getadminaction':
        $result = getAdminActionDataBase($parameters);
        break;
//User
    case 'getuserhistory':
        $result=getUserHistory($parameters);
        break;

    case 'createuserhistory':
        $result=createUserHistory($parameters);
        break; 

    case 'getuser':
        $result = getUserDataBase($parameters);
    	break;

    case 'getuseraction':
        $result = getActionDataBase($parameters);
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
function createAdminHistory($parameters = array()){
    $result = createAdminHistoryDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene Historial de Usuarios de Administración
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getAdminHistory($parameters= array()){
    $result=getAdminHistoryDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    $jTableResult['sql']=$result["sql"];
    $jTableResult['datos']=$parameters;
    return $jTableResult;
}


//User

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Crea datos de historial de Usuarios
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createUserHistory($parameters = array()){
    $result = createUserHistoryDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene Historial de Usuarios
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getUserHistory($parameters= array()){
    $result=getUserHistoryDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    
    return $jTableResult;
}

echo json_encode($result);

?>