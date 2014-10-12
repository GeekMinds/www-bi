<?php
require_once("./models/config.php");
require_once("./models/funcs.mod_tag.php");
header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_POST, $_GET);
error_log("POST PARAMETERS -----> ".json_encode($_POST));
$callback = getCallback($parameters);
$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}


error_log('ACTION -- > ' . $action );
switch ($action) {
    case 'gettags':
        $result['tags'] = getTags($parameters);
    break;
    case 'savetags':
        $result=saveTags($parameters);
    break;
    case 'getpagename':
        $result=getPageName($parameters);
    break;
    default:
    $result['ERROR']='Operacion no definida';
}

if (strlen($callback) > 0) {
    echo $callback . '(' . json_encode($result) . ');';
} else {
    echo json_encode($result);
}
?>