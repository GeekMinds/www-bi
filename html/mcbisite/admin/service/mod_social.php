<?php
require_once("./models/config.php");
require_once("./models/funcs.mod_social.php");
header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'insertsocial':
        $result = insertSocial($parameters);
    break;
    case 'listsocial':
        $result = listSocial();
    break;
    case 'listsites':
        $result = listSites();
    break;
    case 'updatesocial':
        $result = updateSocial($parameters);
    break;
    case 'deletesocial':
        $result = deleteSocial($parameters);
    break;
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}

function insertSocial($parameters) {
    $result =insertSocialDB($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}

function updateSocial($parameters) {
    $result =updateSocialDB($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;
}

function deleteSocial($parameters) {
    $result =deleteSocialDB($parameters);
    $jTableResult = array();
    if($result)
        $jTableResult['Result'] = "OK";
    else
        $jTableResult['Result'] = "Error";

    return $jTableResult;
}


function listSocial() {
    $result =listSocialDB();
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result;
    return $jTableResult;
}

function listSites(){
    $result =listSitesDB();
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result;
    return $jTableResult;
}










if (strlen($callback) > 0) {
    echo $callback . '(' . json_encode($result) . ');';
} else {
    echo json_encode($result);
}
?>