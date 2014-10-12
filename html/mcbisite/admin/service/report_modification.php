<?php
/*


*/

require_once("./models/config.php");
require_once("./models/funcs.report_modification.php");
header('Access-Control-Allow-Origin: *');


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH THE CHIWI
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);


if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'getSites':
        $result = getSitesDataBase($parameters);
    break;

    case 'getPages':
       $result= getPagesDataBase($parameters);
    break;
 
    case 'getModule':
        $result=getModuleDataBase($parameters);
    break;

    case 'getUser':
        $result=getUserAdminDataBase($parameters);
    break;

    case 'listModification':
        $result=listModification($parameters);
    break;

    case 'list_detail':
        $result = listDetailApproval($parameters);
    break;


    case 'list_detailTotal':
        $result = listDetailApprovalTotal($parameters);
    break;



  
	case 'notlogued':
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Usuario no logueado';
        break;
    
    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';

}



//Declaración de funciones


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene Items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listModification($parameters= array()){
    $result=listModificationDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// list all items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function listDetailApproval($parameters = array()) {
    //Get records from database
    $data = listApprovalDetailDataBase($parameters);
    //Return result to jTable
    $jTableResult = array();
    
    if($data){
        $jTableResult['Result'] = "OK";
        $jTableResult['Records'] = $data['rows'];
        $jTableResult['Options'] = $data['rows'];
        $jTableResult['TotalRecordCount'] = $data["count"];
    }else{
        $jTableResult['Result'] = "ERROR";
        $jTableResult['Message'] = "Error inesperado en la base de datos.";
    }

    return $jTableResult;
}


function listDetailApprovalTotal($parameters = array()) {
    //Get records from database
    $data = listApprovalDetailTotalDataBase($parameters);

    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data["result"]; 
    
    return $jTableResult;
}









echo json_encode($result);



?>