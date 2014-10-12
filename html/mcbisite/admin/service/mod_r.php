<?php

/*
 * Author: Javier Cifuentes
 */
require_once("models/config.php");
require_once("models/funcs.modr.php");
header('Access-Control-Allow-Origin: *');

function listForms($parameters = array()) {
    return getAllForms();
}

function updateField($parameters = array()) {
    $par = $parameters['field'];
    $val = $parameters['newval'];
    $moduleid = $parameters['moduleid'];
    $data;
    if ($par == 'enable_share' || $par == 'enable_obtain' || $par == 'enable_compare' || $par == 'enable_favorite' || $par == 'enable_poll' || $par == 'enable_bichat' || $par == 'obtain_online_form' || $par == 'obtain_contact_form' || $par == 'poll_form' || $par == 'obtain_download_link') {
        if($par == 'obtain_download_link'){
            $val = "'".$val."'";
        }
        $data = updateaField($par, $val, $moduleid);
    } else {
        $data['Result'] = "ERROR";
        $data['Message'] = "El campo a actualizar no es valido";
    }
    return $data;
}

$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'listForms':
        $result = listForms($parameters);
        break;
    case 'updateField':
        $result = updateField($parameters);
        break;
    case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
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
