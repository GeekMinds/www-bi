<?php
/*


*/
require_once("./models/config.php");
require_once("./models/funcs.report_tag.php");
header('Access-Control-Allow-Origin: *');

$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'getTag':
        $result = getTagDataBase($parameters);
    break;

    case 'listCount':
       $result= listCount($parameters);
    break;
 
 

  
	case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
        break;
    
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';

}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene Items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listCount($parameters= array()){
    $result=listCountDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    
    return $jTableResult;
}








echo json_encode($result);

?>