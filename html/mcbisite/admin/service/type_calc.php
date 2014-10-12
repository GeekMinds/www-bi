<?php
/*


*/
require_once("./models/config.php");
require_once("./models/funcs.type_calc.php");
header('Access-Control-Allow-Origin: *');




//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// WORKING WITH THE CHIWI
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
$parameters = getParameters($_POST, $_GET);
$callback = getCallback($parameters);
$action = getAction($parameters);

if(!isUserLoggedIn()) { $action = "notlogued";}


switch ($action) {
    //Type Calcs
    case 'updateCalc':
         $result=updateCalc($parameters);
    break;
	
    case 'getcontentType':
        $result=getcontentType($parameters);
    break;

    case 'getcontentTypeCalcs':
    	$result=getcontentTypeCalcs($parameters);	
    break;

    case 'getParamCalc':
        $result=getParamCalc($parameters);
    break;

    case 'updateParamCalc':
         $result=updateParamCalc($parameters);
    break;

    case 'getParamDina':
         $result=getParamDina($parameters);
    break;

    case 'updateParamDina':
        $result=updateParamDina($parameters);
    break;

    case 'deleteParamDina':
        $result=deleteParamDina($parameters);
    break;


    //option abc 
    case 'getPeriod':
         $result=getPeriod($parameters);
    break;

    case 'updatePeriod':
         $result=updatePeriod($parameters);
    break;

    case 'createPeriod':
         $result=createPeriod($parameters);
    break;

    case 'deletePeriod':
         $result=deletePeriod($parameters);
    break;


    case 'getPlazos':
         $result=getPlazos($parameters);
    break;

    case 'updatePlazos':
         $result=updatePlazos($parameters);
    break;

    case 'createPlazos':
         $result=createPlazos($parameters);
    break;

    case 'deletePlazos':
         $result=deletePlazos($parameters);
    break;



    case 'getRan':
         $result=getRan($parameters);
    break;

    case 'updateRan':
         $result=updateRan($parameters);
    break;

    case 'createRan':
         $result=createRan($parameters);
    break;

    case 'deleteRan':
         $result=deleteRan($parameters);
    break;





    case 'getOption':
          $result=getOptionDataBase($parameters);
           
    break;

    case 'getOptionmoney':
         $result=getallmonedasDataBase($parameters);
    break;

    case 'getOptionrango':
         $result=getallrangosDataBase($parameters);
    break;

    case 'getOptionPlazos':
         $result=getOptionPlazosDataBase($parameters);
    break;

    case 'CreateTasa':
        $result=CreateTasa($parameters);
    break;


	
 

    default:
        $result['Result'] = 'ERROR';
        $result['Message'] = 'Operación no definida'.$action;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// create de tasa calc
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function CreateTasa($parameters = array()){
  
    $result = CreateTasaDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;

    return $result;

    
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Get  plazos
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getPlazos($parameters= array()){
  
 $result=getPlazosDataBase($parameters);

    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Get  rangos
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getRan($parameters= array()){
  
 $result=getRanDataBase($parameters);

    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Get  Periode
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getPeriod($parameters= array()){
  
 $result=getPeriodDataBase($parameters);

    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];

    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Get  Calculadora param dina 
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getParamDina($parameters= array()){
  
 $result=getParamDinaDataBase($parameters);

    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// update param dina 
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateParamDina($parameters= array()){
  
 $result=updateParamDinaDataBase($parameters);
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
// delete param dina 
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deleteParamDina($parameters=array()){
  
  $result=deleteParamDinaDataBase($parameters);
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
// Actualiza Calculadora
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateCalc($parameters= array()){
  
 $result=updateCalcDataBase($parameters);
    return $result;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene type calc
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getcontentType($parameters= array()){
    $result=getcontentTypeDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene type calc categorie
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getcontentTypeCalcs($parameters= array()){
    $result=getcontentTypeCalcsDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    return $jTableResult;
}


//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Obtiene parameter calc 
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function getParamCalc($parameters= array()){
    $result=getParamCalcDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Records'] = $result["result"]; 
    $jTableResult['TotalRecordCount']=$result["count"];
    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Actualiza parametros
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateParamCalc($parameters= array()){
  
 $result=updateParamCalcDataBase($parameters);
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
// Update periodo 
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updatePeriod($parameters= array()){
  
 $result=updatePeriodDataBase($parameters);
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
// Crear periodo
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createPeriod($parameters = array()){

    $result = createPeriodDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Elimina periodo
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deletePeriod($parameters=array()){
  
  $result=deletePeriodDataBase($parameters);
 
    return $result;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Update plazos 
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updatePlazos($parameters= array()){
  
 $result=updatePlazosDataBase($parameters);
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
// Crear plazos
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createPlazos($parameters = array()){

    $result = createPlazosDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Elimina plazos
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deletePlazos($parameters=array()){
  
  $result=deletePlazosDataBase($parameters);
 
    return $result;
}



//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Update rango 
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function updateRan($parameters= array()){
  
 $result=updateRanDataBase($parameters);
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
// Crear rango
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function createRan($parameters = array()){

    $result = createRanDataBase($parameters);
    $jTableResult = array();
    $jTableResult['Result'] = "OK";
    $jTableResult['Record'] = $result;

    return $jTableResult;
}

//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
// Elimina rango
//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\//\\
function deleteRan($parameters=array()){
  
  $result=deleteRanDataBase($parameters);
 
    return $result;
}






echo json_encode($result);



?>
