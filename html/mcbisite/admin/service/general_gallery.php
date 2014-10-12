<?php
/*


*/

require_once("./models/config.php");
require_once("./models/funcs.general_gallery.php");
header('Access-Control-Allow-Origin: *');




//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH THE CHIWI
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);

//if(!isUserLoggedIn()) { $action = "notlogued";}
if(!isUserLoggedIn()) { $action = "notlogued";}

switch ($action) {
    case 'getgeneralgallery':
        $result = getGeneralGallery($parameters);
    break;

    case 'creategeneralgallery':
        $result=creategeneralGallery($parameters);
    break;
	 case 'updategeneralgallery':
        $result=updategeneralgallery($parameters);
    break;

    //Gallery Items
	
    case 'getcontentItems':
        $result=getcontentItems($parameters);
    break;
	
   

    case 'createItemsGallery':
        $result=createItemsGallery($parameters);
    break;

     case 'updategalleryitem':
        $result=updateItem($parameters);
    break;

    case 'deletegalleryitem':
        $result=deleteItem($parameters);
    break;


    //tags 

   case 'getalltags':
        $result=getAllTagsDataBase($parameters);
    break;

    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}



/*
{DATA:"ERE"}
*/

//Declaración de funciones



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Actualiza galeria
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updategeneralgallery($parameters = array()){
  
    $result = updategeneralgalleryDataBase($parameters);
    return $result;

    }



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Creación de galeria
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function creategeneralGallery($parameters = array()){
  
    $result = creategeneralgalleryDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;

    return $result;

    
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene galeria 
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getGeneralGallery($parameters) {
    
    //$result = readModuleCarrouselDataBase($parameters['data']);
    $result = getGeneralGalleryDataBase($parameters);

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
    return $jTableResult;

}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene Items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getcontentItems($parameters= array()){
    $result=getcontentItemsDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    return $jTableResult;
}





//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Crear Items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createItemsGallery($parameters = array()){

    $result = createItemsGalleryDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;

    return $jTableResult;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Elimina Items
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deleteItem($parameters=array()){
  
  $result=deleteItemDataBase($parameters);
    $jTableResult=array();
    if($result.error==0){
        $jTableResult['Result'] = "OK";
    }else{
        $jTableResult['Result'] = "ERROR";
    }
    
    $jTableResult['Records'] = $result; 
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Actualiza itemes
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateItem($parameters= array()){
  
 $result=updateItemDataBase($parameters);
    $jTableResult=array();
    if($result.error==0){
        $jTableResult['Result'] = "OK";
    }else{
        $jTableResult['Result'] = "ERROR";
    }
    
    $jTableResult['Records'] = $result; 
    return $jTableResult;

}


echo json_encode($result);



?>