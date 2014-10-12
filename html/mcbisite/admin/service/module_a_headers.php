<?php
require_once("models/config.php");
require_once('models/funcs.module_a_headers.php');
header('Access-Control-Allow-Origin: *');
/*
	Parametros a ser utilizados
*/
$result=null;
$action=$_GET['action'];
$parameters = getParameters($_POST, $_GET);

function addheaders($title_es,$title_en,$link,$sequence){
	global $db,$site_id;
	$result  = array();
        
        $total = intval(countInsert());
        if($total < 4){
            $data = addheadersDB($title_es,$title_en,$link,$site_id,$sequence);
                if($data){
                    $result["Result"] = "OK";
                    $result["Record"] = $data;
                    $result["Message"] = "OK";
                    
                }
                else{
                    $result["Result"] = "ERROR";
                    $result["Message"] = "Error en la Base de datos";
                }       
	
        }
        else
        {
            $result["Result"] = "ERROR";
            $result["Message"] = "No puedes colocar mas de 4 Pestañas";
        }
	
	$db->sql_close();
	return $result;
}


function getheaders($parameters=array()){
	global $db;
	$result  = array();
	$data = getheadersDB($parameters);
	$result["Result"] = "OK";
	//dumpear($data);
	//if($data){
		
		$result["Records"] = $data['result'];
		$result["Message"] = "OK";
		$result['TotalRecordCount'] = $data['count'];
	//}else{
	//	$result["Result"] = "ERROR";
	//	$result["Message"] = "Error en la Base de datos";
	//}
	$db->sql_close();
	return $result;

}


function deleteheaders($id){
	global $db;
	$result  = array();
	$result["Result"] = "OK";
	deleteheadersDB($id);	
	//dumpear($result);
	$db->sql_close();
	return $result;

}

function updateheaders($id,$title_es,$title_en,$link,$sequence){
	global $db,$site_id;
	$result  = array();
	$result["Result"] = "OK";
	updateheadersDB($id,$title_es,$title_en,$link,$site_id,$sequence);
	$db->sql_close();
	return $result;

}


if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
	case 'addheaders':
		$title_es=		$_POST['title_es'];
		$title_en=		$_POST['title_en'];
        $sequence=		$_POST['sequence'];
		$link=		$_POST['link'];
		$result=addheaders($title_es,$title_en,$link,$sequence);
		break;
	case 'deleteheaders':
		$id=		$_POST['id'];
		$result=deleteheaders($id);
		break;
	case 'getheaders':
		$result=getheaders($parameters);
		break;
	case 'updateheaders':
		$title_es=		$_POST['title_es'];
		$title_en=		$_POST['title_en'];
		$link=		$_POST['link'];
        $sequence=		$_POST['sequence'];
		$id=		$_POST['id'];
		$result=updateheaders($id,$title_es,$title_en,$link,$sequence);
		break;
	
}

echo json_encode($result);