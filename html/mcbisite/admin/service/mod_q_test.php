<?php

// Modulo de Ficha tecnica de producto.
require_once("models/config.php");
require_once("models/funcs.mod_q.php");
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
    case 'getInterest':
        $result = getInterests();
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