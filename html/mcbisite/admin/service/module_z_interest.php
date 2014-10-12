<?php
require_once("./models/config.php");
require_once('./models/funcs.module_z_interest.php');
header('Access-Control-Allow-Origin: *');
/*
	Parametros a ser utilizados
*/
$result=null;
$action=$_GET['action'];
$parameters = getParameters($_POST, $_GET);

function addInterest($name_es,$name_en,$interest_type,$img_url,$tags){
	global $db;
	$result  = array();
	$data = addInterestDB($name_es,$name_en,$interest_type,$img_url,$tags);
	if($data){
		$result["Result"] = "OK";
		$result["Record"] = $data;
		$result["Message"] = "OK";
	}else{
		$result["Result"] = "ERROR";
		$result["Message"] = "Error en la Base de datos";
	}
	$db->sql_close();
	return $result;
}


function getInterests($parameters=array()){
	global $db;
	$result  = array();
	$data = getInterestDB($parameters);
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


function deleteInterest($id){
	global $db;
	$result  = array();
	
	$data = deleteInterestDB($id);
        if($data){
            $result["Result"] = "OK";
            $result["Message"] = "Eliminado con éxito";
        }else{
            $result["Result"] = "ERROR";
            $result["Message"] = "El interés esta asociado a otros elementos.";
        }
	//dumpear($result);
	$db->sql_close();
	return $result;

}

function updateInterest($id,$name_es,$name_en,$interest_type,$img_url,$tags){
	global $db;
	$result  = array();
	$result["Result"] = "OK";
	updateInterestDB($id,$name_es,$name_en,$interest_type,$img_url,$tags);
	$db->sql_close();
	return $result;

}


switch ($action) {
	case 'addinterest':
		$name_es=		$_POST['name_es'];
		$name_en=		$_POST['name_en'];
		$interest_type=	$_POST['interest_type'];
		$img_url=		$_POST['img_url'];
		$tags=		$_POST['tags'];
		$result=addInterest($name_es,$name_en,$interest_type,$img_url,$tags);
		break;
	case 'deleteinterest':
		$id=		$_POST['id'];
		$result=deleteInterest($id);
		break;
	case 'getinterests':
		$result=getInterests($parameters);
		break;
	case 'getalltags':
		$result=getAllTagsDB($parameters);
		break;
	case 'updateinterest':
		$name_es=		$_POST['name_es'];
		$name_en=		$_POST['name_en'];
		$interest_type=	$_POST['interest_type'];
		$img_url=		$_POST['img_url'];
		$id=		$_POST['id'];
		$tags=		$_POST['tags'];
		$result=updateInterest($id,$name_es,$name_en,$interest_type,$img_url,$tags);
		break;
	
}

echo json_encode($result);
?>