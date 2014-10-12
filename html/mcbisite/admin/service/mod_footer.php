<?php
require_once("./models/config.php");
require_once("./models/funcs.mod_footer.php");
header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);

switch ($action) {
    case 'getfooter':
        $result = getFooter();
    break;
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}

function getFooter() {
    $result =getFooterDB();
    return $result;
}


if (strlen($callback) > 0) {
    echo $callback . '(' . json_encode($result) . ');';
} else {
    echo json_encode($result);
}
?>