<?php
// Modulo de Ficha tecnica de producto.
require_once("models/config.php");
require_once("models/funcs.mod_q.php");
//header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'update':
        $result = createPageAndSaveContents($parameters);
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
    case 'getcontentlist':
        $result = getMoudelList($parameters);
        break;
    case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
        break;
    case 'getInterest':
        $result = getInterests();
        break;
    case 'listbeneficios':
        $result = getBeneficios($parameters);
        break;
    case 'createbeneficio':
        $result = createBeneficio($parameters);
        break;
    case 'updatebeneficio':
        $result = updateBeneficio($parameters);
        break;
    case 'deletebeneficio':
        $result = deleteBeneficio($parameters);
        break;
    case 'listrequisitos':
        $result = getRequisitos($parameters);
        break;
    case 'createrequisito':
        $result = createRequisito($parameters);
        break;
    case 'updaterequisito':
        $result = updateRequisito($parameters);
        break;
    case 'deleterequisito':
        $result = deleteRequisito($parameters);
        break;
    case 'listcaracteristicas':
        $result = getCaracteristicas($parameters);
        break;
    case 'createcaracteristica':
        $result = createCaracteristica($parameters);
        break;
    case 'updatecaracteristica':
        $result = updateCaracteristica($parameters);
        break;
    case 'deletecaracteristica':
        $result = deleteCaracteristica($parameters);
        break;
    case 'listdirectorios':
        $result = getDirectorios($parameters);
        break;
    case 'createdirectorio':
        $result = createDirectorio($parameters);
        break;
    case 'updatedirectorio':
        $result = updateDirectorio($parameters);
        break;
    case 'deletedirectorio':
        $result = deleteDirectorio($parameters);
        break;
    case 'getmain':
        $result = getMainArea($parameters);
        break;
    case 'savemain':
        $result = saveMainArea($parameters);
        break;
    case 'getgallery':
         $result = getGallery($parameters);
        break;
    case 'savegallery':
        $result = saveGallery($parameters);
        break;
    case 'saveinterests':
        $result = saveInterests($parameters);
        break;
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
        return $result;
}

function getInterests() {
    global $db, $db_table_prefix;
    $sql2 = "SELECT  id,name_es,name_en,interest_type,img_url FROM " . $db_table_prefix . "interest ";
    $rs = $db->sql_query($sql2);
    $rs2 = $db->sql_fetchrowset($rs);
    return $rs2;
}

function saveMoudelProductoContent($parameters = array()) {
    if ($parameters['data']['content_id'] != '') {
        $result = createModuleProductoDataBase($parameters['data']);
//    $addBene = createBene($parameters['beneficios_es'],$parameters['beneficios_en'], $parameters['data']);
//    $addReq =  createReq($parameters['requisitos_es'],$parameters['requisitos_en'], $parameters['data']);
    } else {
        $result = createModuleProductoDataBase($parameters['data']);
    }
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
//    $jTableResult['Bene'] = $addBene;
//    $jTableResult['Req'] = $addReq;
    return $jTableResult;
}

function getMoudelProductoContent($parameters = array()) {
    $result = queryModuleProductoDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}
function getMoudelList($parameters = array()) {
    $result = getProductsDataBaseList();
    $jTableResult = array();
    $jTableResult['error'] = "0";
    $jTableResult['result'] = $result;
    return $jTableResult;
}
function createPageAndSaveContents($parameters = array()) {
    if ($parameters['data']['content_id'] == '-1') {
        $result = createPageAndSaveContentsDB($parameters);
    } else {
        $result = updateProductDB($parameters);
    }
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}

if (strlen($callback) > 0) {
    echo $callback . '(' . json_encode($result) . ');';
} else {
    echo json_encode($result);
}


?>