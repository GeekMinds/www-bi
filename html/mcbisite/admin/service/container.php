<?php
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// LIBRARIES
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
require_once("./models/config.php");
require_once("./models/funcs.container.php");
header('Access-Control-Allow-Origin: *');



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// getContainerDistribution
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getContainerDistribution($parameters=array()){
	//Get records from database
	global $db;
	$data = getContainerDistributionDataBase($parameters);
	//Return result
	$result = array();
	$result['error'] = "0";
	$result['message'] = "";
	$result['result'] = $data;
	
	//Closing Data Base
	$db->sql_close();
	return $result;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// getContainerList
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getContainerList($parameters=array()){
	//Get records from database
	global $db;
	$data = getContainerDistributionDataBase($parameters);
	//Return result
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $data;
    $jTableResult['Options'] = $data;
    $jTableResult['TotalRecordCount'] = count($data);
	
	//Closing Data Base
	$db->sql_close();
	return $jTableResult;
}





//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH PARAMETERS
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);

error_log(json_encode($parameters));
$callback = getCallback($parameters);
$action = getAction($parameters);

if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
	case 'getcontainerdistribution':
		$result = getContainerDistribution($parameters);
	break;
	case 'getcontainerlist':
		$result = getContainerList($parameters);
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