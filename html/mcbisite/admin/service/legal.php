<?php

/*
 * Author: Javier Cifuentes
 */
require_once("models/config.php");
require_once("models/funcs.legal.php");
header('Access-Control-Allow-Origin: *');
$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);

switch ($action) {
    case 'updateField':
        $result = updateField($parameters);
        break;
    case 'getField':
        $result = getField($parameters);
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
?>

