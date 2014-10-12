<?php
/*


*/

require_once("./models/config.php");
require_once("./models/funcs.search_content.php");
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


    //action contente search
	

    case 'createContentSearch':
        $result=createContentSearch($parameters);
    break;
	
  
     case 'updateContentSearch':
        $result=updateContentSearch($parameters);
    break;

    case 'getContentSearch';
        $result=getContentSearch($parameters);
    break;



  

//load data select 

   case 'getallSearch':
        $result=getAllSearchDataBase($parameters);
            echo  json_encode($result);
    break;

//load content parameter 
    case 'getcontent':
        $result=getcontentDataBase($parameters);

    
    break;    


    case 'getcontentEdit':
        $result=getcontentEditDataBase($parameters);
    
    break;





    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida';
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene content search informa
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getcontentEdit($parameters= array()){
  //  $result=getcontentEditDataBase($parameters);
  // echo $result;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene content search 
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getcontent($parameters= array()){
 //   $result=getcontentDataBase($parameters);
  // echo $result;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Creación de contenido
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createContentSearch($parameters = array()){
  
    $result = createContentSearchDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;

   // return $result;
    echo  json_encode($result);
    }


 //\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Actualiza contenido
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateContentSearch($parameters = array()){
  
    $result = updateContentSearchDataBase($parameters);
 //   return $result;
    echo  json_encode($result);

    }

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene search content  
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getContentSearch($parameters) {
    
    //$result = readModuleCarrouselDataBase($parameters['data']);
    $result = getContentSearchDataBase($parameters);

    //Return result to jTable
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;
   // return $jTableResult;

echo  json_encode($jTableResult);

}
   

    









?>
