<?php
require_once("models/config.php");
require_once("models/funcs.mod_p.php");
header('Access-Control-Allow-Origin: *');

$result=null;
function createModule($parameters){
	return createModuleDB($parameters);
}

function readModule($parameters){
	return readModuleDB($parameters);
}

function updateModule($parameters){
	return updateModuleDB($parameters);
}

function listComments($parameters){
    $result=listCommentsDB($parameters);
    $jTableResult = array();
    $jTableResult['TotalRecordCount']=countComments();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result;
    return $jTableResult; 
}


function getComment($parameters){
    $result=getCommentDB($parameters);
    return $result; 
}


function updateCommentState($parameters){
    $result=updateCommentStateDB($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult; 
}
$parameters = getParameters($_POST, $_GET);
error_log(json_encode($parameters));
$callback = getCallback($parameters);
$action = getAction($parameters);

switch ($action) {
    case 'createmodule':
        $result['Result'] = createModule($parameters);
    break;
    case 'readmodule':
        $result['Result'] = readModule($parameters);
    break;
    case 'updatemodule':
        $result['Result'] = updateModule($parameters);
    break;
    case 'getcomment':
        $result= getComment($parameters);
    break;
     case 'updatecommentstate':
        $result= updateCommentState($parameters);
    break;
    case 'listcomments':
        $result=listComments($parameters);
    break;

      case 'readpages':
        $result=readPages();
    break;
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}

if (strlen($callback) > 0) {

    echo $callback . '(' . json_encode($result) . ');';
} else {
    //error_log(json_encode($result));
    echo json_encode($result);
}

?>
