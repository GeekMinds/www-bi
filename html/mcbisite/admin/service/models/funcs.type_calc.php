<?php


//get alls item 
function getcontentTypeDataBase($parameters){

    global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'description ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';

    
     $data = array();
     $sql = "";
     $sql_size = "";



         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM";
               $sql .= "   (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id,description ";
               $sql .= "    FROM  [".$db_name."].[dbo].[".$db_table_prefix."type_calc] )  AS user_with_numbers";
               $sql .= " WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{
            $sql = "SELECT * FROM [".$db_name."].[dbo].[".$db_table_prefix."type_calc] ";
           }

         $sql_size = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."type_calc]";
    

    //consulta los items
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrowset($result);
  
    //retorna el count de items
    $result_count = $db->sql_query($sql_size);
    $result_count= $db->sql_fetchrow($result_count);
 
        $data['result'] = $result;
        $data["count"] = $result_count['RecordCount'];

    return $data;



}
//end get alls item 



//get alls item 
function getcontentTypeCalcsDataBase($parameters){

    global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'title ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    $id=$db->sql_escape($parameters["id"]);

    
     $data = array();
     $sql = "";
     $sql_size = "";

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM";
               $sql .= "   (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, id,type_calc_id,title,note ";
               $sql .= "    FROM  [".$db_name."].[dbo].[".$db_table_prefix."calc] WHERE type_calc_id=".$id.")  AS user_with_numbers";
               $sql .= " WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{
            $sql = "SELECT * FROM [".$db_name."].[dbo].[".$db_table_prefix."calc] WHERE type_calc_id=".$id."";
           }

         $sql_size = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."calc] WHERE type_calc_id=".$id."";
    

    //consulta los items
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrowset($result);
  
    //retorna el count de items
    $result_count = $db->sql_query($sql_size);
    $result_count= $db->sql_fetchrow($result_count);
 
        $data['result'] = $result;
        $data["count"] = $result_count['RecordCount'];

    return $data;



}
//end get alls item 


//get parameter calc 

function getParamCalcDataBase($parameters){


    global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'description ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    $id=$db->sql_escape($parameters["id"]);

    
     $data = array();
     $sql = "";
     $sql_size = "";

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM";
               $sql .= "   (SELECT ROW_NUMBER() OVER (ORDER BY p.".$parameters['jtsorting'].") AS Row, ";
               $sql .= " p.id, p.value ,p.parameters_type_id, mn.simbol as 'moneda_id' ,p.description,p.text"; 
               $sql .= "    FROM  [".$db_name."].[dbo].[".$db_table_prefix."parameters] p inner join parameter_calc pc on pc.calc_id=".$id." and p.id=pc.parameters_id ";
               $sql .= " INNER JOIN moneda mn on mn.id =p.moneda_id";
               $sql .= " )  AS user_with_numbers";
               $sql .= " WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{
            $sql = " SELECT p.id, p.value ,p.parameters_type_id, mn.simbol as 'moneda_id' ,p.description,p.text FROM [".$db_name."].[dbo].[".$db_table_prefix."parameters] p ";
            $sql .= " INNER JOIN parameter_calc pc on pc.calc_id=".$id." p.id=pc.parameters_id ";
            $sql .= " INNER JOIN moneda mn on mn.id =p.moneda_id";
           }

         $sql_size = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."parameters] p ";
         $sql_size .= " INNER JOIN parameter_calc pc on pc.calc_id=".$id." and p.id=pc.parameters_id  ";
    


    //consulta los items
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrowset($result);
  
    //retorna el count de items
    $result_count = $db->sql_query($sql_size);
    $result_count= $db->sql_fetchrow($result_count);
 
        $data['result'] = $result;
        $data["count"] = $result_count['RecordCount'];

       


    return $data;



}

// end get parameter calc


//update parametros
function updateParamCalcDataBase($parameters){

   global $db,$db_table_prefix, $db_name; 
     $data = array();
    //$parameters_type_id=$db->sql_escape($_POST["parameters_type_id"]);
     $id=$db->sql_escape($parameters['id']);
     $value=$db->sql_escape($parameters['value']);
     $description=$db->sql_escape($parameters['description']);
     $text = $db->sql_escape($parameters['text']);

    $sql  =" UPDATE [".$db_name."].[dbo].[".$db_table_prefix."parameters] set ";
    if (is_numeric($value)){
      $sql .=" value ='{$value}' ,description='{$description}'  ";
        }else{
      $sql .=" description='{$description}' ,text='{$text}' ";    
        }
    $sql .=" where id='{$id}'";

    //ejecutar el query 
    $result = $db->sql_query($sql);
  
    if ($result) {
        $data['error'] = "0";
      $data['msj'] = "Datos actualizados exitósamente";
     
    } else {
        $data['error'] = "100";
        $data['msj'] ="Error actualizando los datos";
     
    }

    return $data;


}
// end parametros

// update calc 

function updateCalcDataBase($parameters)
{

     global $db,$db_table_prefix, $db_name; 
     $data = array();
    //$parameters_type_id=$db->sql_escape($_POST["parameters_type_id"]);
     $id=$db->sql_escape($parameters['id']);
     $title=$db->sql_escape($parameters['title']);
     $note=$db->sql_escape($parameters['note']);
     

    $sql  =" UPDATE [".$db_name."].[dbo].[".$db_table_prefix."calc] set ";
    $sql .=" title='{$title}' ,note='{$note}' ";    
    $sql .=" where id='{$id}'";

    //ejecutar el query 
    $result = $db->sql_query($sql);
  
    if ($result) {
        $data['error'] = "0";
      $data['msj'] = "Datos actualizados exitósamente";
     
    } else {
        $data['error'] = "100";
        $data['msj'] ="Error actualizando los datos";
     
    }

    return $data;


}

//end update calc

//get params dinamic 


function getParamDinaDataBase($parameters){


    global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'valor ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    $id=$db->sql_escape($parameters["id"]);

    
     $data = array();
     $sql = "";
     $sql_size = "";

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM";
               $sql .= "   (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, ";
               $sql .= " tn.id , tn.rangos_id,tn.valor,m.simbol, m.id as 'simbol_id',tn.plazos_id,concat (ISNULL(p.value,0),' Pagos') as plazos,tp.description ,r.min,r.max"; 
               $sql .= "    FROM  [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc ";
               $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.id=tc.tasas_id ";
               $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."moneda] m on m.id=tc.moneda_id ";
               $sql .= " LEFT JOIN [".$db_name."].[dbo].[".$db_table_prefix."plazos] p on p.id=tn.plazos_id";
               $sql .= " LEFT JOIN [".$db_name."].[dbo].[".$db_table_prefix."type_period] tp on tp.id=p.type_period_id";
               $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."rangos] r on r.id=tn.rangos_id";
               $sql .= " where tc.calc_id=".$id;
               $sql .= " )  AS user_with_numbers";
               $sql .= " WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{
            $sql = " SELECT tn.id , tn.rangos_id,tn.valor,m.simbol, m.id as 'simbol_id',tn.plazos_id,concat (ISNULL(p.value,0),' Pagos') as plazos,tp.description ,r.min,r.max FROM [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc ";
               $sql .= "    FROM  [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc ";
               $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.id=tc.tasas_id ";
               $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."moneda] m on m.id=tc.moneda_id ";
               $sql .= " LEFT JOIN [".$db_name."].[dbo].[".$db_table_prefix."plazos] p on p.id=tn.plazos_id";
               $sql .= " LEFT JOIN [".$db_name."].[dbo].[".$db_table_prefix."type_period] tp on tp.id=p.type_period_id";
               $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."rangos] r on r.id=tn.rangos_id";
               $sql .= " where tc.calc_id=".$id;
           }

         $sql_size = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc ";
         $sql_size .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.id=tc.tasas_id ";
         $sql_size .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."moneda] m on m.id=tc.moneda_id ";
         $sql_size .= " LEFT JOIN [".$db_name."].[dbo].[".$db_table_prefix."plazos] p on p.id=tn.plazos_id";
         $sql_size .= " LEFT JOIN [".$db_name."].[dbo].[".$db_table_prefix."type_period] tp on tp.id=p.type_period_id";
         $sql_size .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."rangos] r on r.id=tn.rangos_id";
         $sql_size .= " where tc.calc_id=".$id;
        
        //consulta los items
        $result = $db->sql_query($sql);
        $result= $db->sql_fetchrowset($result);
  
        //retorna el count de items
        $result_count = $db->sql_query($sql_size);
        $result_count= $db->sql_fetchrow($result_count);
 
        $data['result'] = $result;
        $data["count"] = $result_count['RecordCount'];
    

       


    return $data;



}
//end get params dinamic


//update parametros dinamic
function updateParamDinaDataBase($parameters){

   global $db,$db_table_prefix, $db_name; 
     $data = array();
  
     $id=$db->sql_escape($parameters['id']);
     $valor=$db->sql_escape($parameters['valor']);
    

    $sql  =" UPDATE [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] set ";
    $sql .=" valor ='{$valor}'  ";
    $sql .=" where id='{$id}'";

    //ejecutar el query 
    $result = $db->sql_query($sql);
  
    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos actualizados exitósamente";
     
    } else {
        $data['error'] = "100";
        $data['msj'] ="Error actualizando los datos";
     
    }

    return $data;


}
// end update parametros dina 



//get all periodes 

function getPeriodDataBase($parameters){


    global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'description ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    $id=$db->sql_escape($parameters["id_calc"]);

    
     $data = array();
     $sql = "";
     $sql_size = "";


         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM";
               $sql .= " (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, ";
               $sql .= " tp.id,tp.description,tp.day_value FROM [".$db_name."].[dbo].[".$db_table_prefix."type_period] as tp";
               $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."plazos] p on tp.id=p.type_period_id";
               $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.plazos_id=p.id";
               $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id; 
               $sql .= " group by tp.id,tp.description,tp.day_value )  AS user_with_numbers";
               $sql .= " WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{
            $sql = " SELECT  DISTINCT tp.id,tp.description,tp.day_value FROM  [".$db_name."].[dbo].[".$db_table_prefix."type_period] as tp";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."plazos] p on tp.id=p.type_period_id";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.plazos_id=p.id";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id; 
             
           }



         $sql_size = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."type_period]  ";
        

        //consulta los items
        $result = $db->sql_query($sql);
        $result= $db->sql_fetchrowset($result);
  
        //retorna el count de items
        $result_count = $db->sql_query($sql_size);
        $result_count= $db->sql_fetchrow($result_count);
 
        $data['result'] = $result;
        $data["count"] = count($result);
    

    return $data;



}

// end get all periodes


// get ranges 

function getRanDataBase($parameters){


    global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'moneda_id ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    $id=$db->sql_escape($parameters["id_calc"]);

    
     $data = array();
     $sql = "";
     $sql_size = "";

 

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM";
               $sql .= " (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, ";
               $sql .= " r.id,r.min,r.max,(select m.simbol from moneda m  where m.id=tc.moneda_id) as 'simbolo' "; 
               $sql .= " FROM  [".$db_name."].[dbo].[".$db_table_prefix."rangos] r  ";
               $sql .= " inner join [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.rangos_id=r.id";
               $sql .= " inner join [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id;
               $sql .= " GROUP BY r.id,r.min,r.max,tc.moneda_id";
               $sql .= " )  AS user_with_numbers";
               $sql .= " WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{
            $sql  = " SELECT  r.id,r.min,r.max,(select m.simbol from moneda m  where m.id=tc.moneda_id) as 'simbolo' ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."rangos] r";
            $sql .= " inner join [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.rangos_id=r.id";
            $sql .= " inner join [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id;
            $sql .= " GROUP BY r.id,r.min,r.max,tc.moneda_id";

            }



         $sql_size = "SELECT ";
         $sql_size .= " r.id,r.min,r.max,(select m.simbol from moneda m  where m.id=tc.moneda_id) as 'simbolo' "; 
         $sql_size .= " FROM  [".$db_name."].[dbo].[".$db_table_prefix."rangos] r  ";
         $sql_size .= " inner join [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.rangos_id=r.id";
         $sql_size .= " inner join [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id;
         $sql_size .= " GROUP BY r.id,r.min,r.max,tc.moneda_id";

         
        //consulta los items
        $result = $db->sql_query($sql);
        $result= $db->sql_fetchrowset($result);


  
        //retorna el count de items
        $result_count = $db->sql_query($sql_size);
        $result_count= $db->sql_fetchrowset($result_count);
 
        $data['result'] = $result;
        $data["count"] = count($result_count);
    



    return $data;



}

//end get ragnes 


//get plazos

function getPlazosDataBase($parameters){


    global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'value ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    $id=$db->sql_escape($parameters["id"]);

    
     $data = array();
     $sql = "";
     $sql_size = "";

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM";
               $sql .= " (SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, ";
               $sql .= " id,value"; 
               $sql .= " FROM  [".$db_name."].[dbo].[".$db_table_prefix."plazos] where type_period_id=".$id." ";
               $sql .= " )  AS user_with_numbers";
               $sql .= " WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{
            $sql = " SELECT  id,value  FROM  [".$db_name."].[dbo].[".$db_table_prefix."plazos] where type_period_id=".$id." ";
             
           }

         $sql_size = "SELECT COUNT(*) AS RecordCount FROM [".$db_name."].[dbo].[".$db_table_prefix."plazos] where type_period_id=".$id."";


     
        

        //consulta los items
        $result = $db->sql_query($sql);
        $result= $db->sql_fetchrowset($result);
  
        //retorna el count de items
        $result_count = $db->sql_query($sql_size);
        $result_count= $db->sql_fetchrow($result_count);
 
        $data['result'] = $result;
        $data["count"] = $result_count['RecordCount'];
    



    return $data;



}

//end get plazos 

//add period options

function getOptionDataBase($parameters) {
     global $db, $db_table_prefix,$db_name;


    
       $id=$db->sql_escape($parameters["id_calc"]);
    

    $data = array();
            $sql = " SELECT  DISTINCT tp.id,tp.description,tp.day_value FROM   [".$db_name."].[dbo].[".$db_table_prefix."type_period] as tp";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."plazos] p on tp.id=p.type_period_id";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.plazos_id=p.id";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id; 

   
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);

return $result;

    
}

//end add period options


//create periodos 
function createPeriodDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();
    
    $data1 = array();
    

    $description=$db->sql_escape($parameters['description']);
    $day_value=$db->sql_escape($parameters['day_value']);
 
        $sql = " INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."type_period]";
        $sql .="( description, day_value)";
        $sql .= "OUTPUT Inserted.ID as id";
        $sql .=" VALUES( '{$description}','{$day_value}')";         
        $result = $db->sql_query($sql);
        $row= $db->sql_fetchrow($result);



//agregar el id de la 
    $sql = "SELECT * FROM [".$db_name."].[dbo].[".$db_table_prefix."type_period] WHERE  id =".$row['id'];
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $data = $row;
    return $data;
}

//end create periodos 


//update periodes

function updatePeriodDataBase($parameters){

   global $db,$db_table_prefix, $db_name; 
     $data = array();
    //$parameters_type_id=$db->sql_escape($_POST["parameters_type_id"]);
     $id=$db->sql_escape($parameters['id']);
     $description=$db->sql_escape($parameters['description']);
     $day_value=$db->sql_escape($parameters['day_value']);
   


    $sql  =" UPDATE [".$db_name."].[dbo].[".$db_table_prefix."type_period] set ";
    $sql .=" description ='{$description}',day_value='{$day_value}' ";
    $sql .=" where id='{$id}'";
 
    $result = $db->sql_query($sql);

    if ($result) {
        $data['error'] = "0";
      $data['msj'] = "Datos actualizados exitósamente";
     
    } else {
        $data['error'] = "100";
        $data['msj'] ="Error al actualizar los datos";
     
    }

    return $data;


}


//end update periodes


//delete periodo

function deletePeriodDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();
          
           
             $id=$db->sql_escape($parameters['id']);


             $sql ="";
             $sql .="SELECT COUNT(1)  as exis FROM [".$db_name."].[dbo].[".$db_table_prefix."plazos] where type_period_id= ".$id;
             $result = $db->sql_query($sql);
             $row= $db->sql_fetchrow($result);
          

             if ($row['exis']>0){
                $data['Result']="ERROR";
                $data['Message'] = "No se puede eliminar el periodo ya que esta amarrado a plazos";

                  }else{

              $sql ="";
              $sql .="DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."type_period] where id= ".$id;
              $result = $db->sql_query($sql);

                   if ($result) {
                        $data['Result'] = "OK";
                        $data['Message'] = "Datos eliminados exitósamente";
                    } else {
                        $data['Result'] = "ERROR";
                        $data['Message'] = "No se pudieron eliminar los datos";
                      }
            }

           
            return $data;

}

//end delete periodo

//_________--------------------_____________


//create plazos 
function createPlazosDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();
    
    $data1 = array();
    

    $value=$db->sql_escape($parameters['value']);
    $id_period=$db->sql_escape($parameters['id_period']);
 
        $sql = " INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."plazos]";
        $sql .="( value, type_period_id)";
        $sql .= "OUTPUT Inserted.ID as id";
        $sql .=" VALUES( '{$value}','{$id_period}')";         
        $result = $db->sql_query($sql);
        $row= $db->sql_fetchrow($result);



//agregar el id de la  
    $sql = "SELECT * FROM [".$db_name."].[dbo].[".$db_table_prefix."plazos] WHERE  id =".$row['id'];
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $data = $row;
    return $data;
}

//end create plazos 


//update plazos

function updatePlazosDataBase($parameters){

   global $db,$db_table_prefix, $db_name; 
     $data = array();
    //$parameters_type_id=$db->sql_escape($_POST["parameters_type_id"]);
     $id=$db->sql_escape($parameters['id']);
     $value=$db->sql_escape($parameters['value']);
     
   


    $sql  =" UPDATE [".$db_name."].[dbo].[".$db_table_prefix."plazos] set ";
    $sql .=" value ='{$value}' ";
    $sql .=" where id='{$id}'";
 
    $result = $db->sql_query($sql);

    if ($result) {
        $data['error'] = "0";
      $data['msj'] = "Datos actualizados exitósamente";
     
    } else {
        $data['error'] = "100";
        $data['msj'] ="Error al actualizar los datos";
     
    }

    return $data;


}


//end update plazos


//delete plazos

function deletePlazosDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();
          
           
             $id=$db->sql_escape($parameters['id']);


             $sql ="";
             $sql .="SELECT COUNT(1)  as exis FROM [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] where plazos_id= ".$id;
             $result = $db->sql_query($sql);
             $row= $db->sql_fetchrow($result);
          

             if ($row['exis']>0){
                $data['Result']="ERROR";
                $data['Message'] = "No se puede eliminar el plazo ya que esta amarrado a una tasa";

                  }else{

              $sql ="";
              $sql .="DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."plazos] where id= ".$id;
              $result = $db->sql_query($sql);

                   if ($result) {
                        $data['Result'] = "OK";
                        $data['Message'] = "Datos eliminados exitósamente";
                    } else {
                        $data['Result'] = "ERROR";
                        $data['Message'] = "No se pudieron eliminar los datos";
                      }
            }

           
            return $data;

}

//end delete plazos

//----------__________------------_________



//create rango 
function createRanDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();
    
    $data1 = array();
    

    $max=$db->sql_escape($parameters['max']);
    $min=$db->sql_escape($parameters['min']);
 
        $sql = " INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."rangos]";
        $sql .="( max, min)";
        $sql .= "OUTPUT Inserted.ID as id";
        $sql .=" VALUES( '{$max}','{$min}')";         
        $result = $db->sql_query($sql);
        $row= $db->sql_fetchrow($result);



//agregar el id de la 
    $sql = "SELECT * FROM [".$db_name."].[dbo].[".$db_table_prefix."rangos] WHERE  id =".$row['id'];
    $result = $db->sql_query($sql);
    $row = $db->sql_fetchrow($result);
    $data = $row;
    return $data;
}

//end create rango 


//update rango

function updateRanDataBase($parameters){

   global $db,$db_table_prefix, $db_name; 
     $data = array();
    //$parameters_type_id=$db->sql_escape($_POST["parameters_type_id"]);
     $id=$db->sql_escape($parameters['id']);
     $min=$db->sql_escape($parameters['min']);
     $max=$db->sql_escape($parameters['max']);
     
   


    $sql  =" UPDATE [".$db_name."].[dbo].[".$db_table_prefix."rangos] set ";
    $sql .=" min ='{$min}',max ='{$max}' ";
    $sql .=" where id='{$id}'";
 
    $result = $db->sql_query($sql);

    if ($result) {
        $data['error'] = "0";
      $data['msj'] = "Datos actualizados exitósamente";
     
    } else {
        $data['error'] = "100";
        $data['msj'] ="Error al actualizar los datos";
     
    }

    return $data;


}


//end update rango


//delete rango

function deleteRanDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();
          
           
             $id=$db->sql_escape($parameters['id']);

             $sql ="";
             $sql .="SELECT COUNT(1)  as exis FROM [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] where rangos_id=".$id;
             $result = $db->sql_query($sql);
             $row= $db->sql_fetchrow($result);
          

             if ($row['exis']>0){
                $data['Result']="ERROR";
                $data['Message'] = "No se puede eliminar el rango ya que esta amarrado a una tasa";

                  }else{

              $sql ="";
              $sql .="DELETE FROM [".$db_name."].[dbo].[".$db_table_prefix."rangos] where id=".$id;
              $result = $db->sql_query($sql);

                   if ($result) {
                        $data['Result'] = "OK";
                        $data['Message'] = "Datos eliminados exitósamente";
                    } else {
                        $data['Result'] = "ERROR";
                        $data['Message'] = "No se pudieron eliminar los datos";
                      }
            }

           
            return $data;

}

//end delete rango


//get all option plazos

function getallplazosDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
  $data = array();
    $sql = "select p.id as Value, concat(p.value,' pagos , ',tp.description) as DisplayText from plazos  p inner join type_period tp on tp.id=p.type_period_id  ";
  $result = $db->sql_query($sql);
  $row = $db->sql_fetchrowset($result);
  $data = $row;

  return $data;
}


//end get all option plazos



//get all option rangos

function getallrangosDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
             $id=$db->sql_escape($parameters['id_calc']);
             $id_money=$db->sql_escape($parameters['id_money']);
  
            $sql  = " SELECT r.id, concat('Minimo: ',cast ((r.min) as decimal(10,2)),'       Maximo: ',cast ((r.max) as decimal(10,2))) as description ";
            $sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."rangos] r";
            $sql .= " inner join [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.rangos_id=r.id";
            $sql .= " inner join [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id;
            $sql .= " and tc.moneda_id=".$id_money." GROUP BY r.id,r.min,r.max,tc.moneda_id";

    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);

  return $result;
}
//end get all option rangos 


//get all option plazos

function getOptionPlazosDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
             $id=$db->sql_escape($parameters['id_calc']);
  
            $sql  =" SELECT cast(('000') as char) as id , 'Sin Plazos' as description union all";
            $sql .=" ( select distinct cast((p.id) as char) as id,cast ((p.value) as char) as description FROM [bisite02].[dbo].[tasas_new] as tn";
            $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id;
            $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."plazos] p on p.id=tn.plazos_id)";


    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);

  return $result;
}


//end get all optioni plazos 


//get all money 

function getallmonedasDataBase($parameters){
  global $db,$db_table_prefix, $db_name; 
  
    $sql = "SELECT  id  , simbol as description  FROM [".$db_name."].[dbo].[".$db_table_prefix."moneda]";
    $result = $db->sql_query($sql);
    $result = $db->sql_fetchrowset($result);

  return $result;
}

//end get all money 


//create tasa 

function CreateTasaDataBase($parameters = array()){
  
 global $db, $db_table_prefix,$db_name;
    $data = array();


    
    $nuevos=$db->sql_escape($parameters['nuevos']);
    $id_calc=$db->sql_escape($parameters['id_calc']);
    $valor=$db->sql_escape($parameters['txt_valor']);
    $moneda=$db->sql_escape($parameters['cmb_moneda']);
    $rango=$db->sql_escape($parameters['cmb_rango']);
    $plazo=$db->sql_escape($parameters['cmb_plazo']);


       if (empty($nuevos)){



        $sql  ="";
        $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] (rangos_id,valor,plazos_id) VALUES ( ";
            if (strlen($plazo)>0){
        $sql .=" '{$rango}','{$valor}','{$plazo}'";
          }else{
        $sql .="'{$rango}','{$valor}' ";
          }
        $sql .=" ) ;";
        $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] (calc_id,tasas_id,moneda_id) VALUES (";
        $sql .=" '{$id_calc}',(select max(id) from [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] ),'{$moneda}' ) ;";

     }else{


$total_nuevos =explode('|', substr($nuevos,0,-1));

  
      switch (count($total_nuevos)) {
    case 1:
     
      if (in_array("plazo", $total_nuevos) ){
          
          //plazo nuevo 
        
           $cmb_period=$db->sql_escape($parameters['cmb_period']);
           $txt_valor_plazo=$db->sql_escape($parameters['txt_valor_plazo']);

             $sql  ="SELECT COUNT(1) AS exis from [".$db_name."].[dbo].[".$db_table_prefix."plazos] where value=".$txt_valor_plazo." and type_period_id= ".$cmb_period;

             $result = $db->sql_query($sql);
             $row= $db->sql_fetchrow($result);
          

             if ($row['exis']>0){
                $data['Result']="ERROR";
                $data['Message'] = "Ya existe un plazo con este valor";

                  }else{

              $sql ="";
              $sql .="INSERT INTO   [".$db_name."].[dbo].[".$db_table_prefix."plazos] (value,type_period_id)  ";
              $sql .= "OUTPUT Inserted.ID as id";
              $sql .=" VALUES ('{$txt_valor_plazo}','{$cmb_period}')";

              $result = $db->sql_query($sql);

                   if ($result) {
                        $row= $db->sql_fetchrow($result);

                          $sql  ="";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] (rangos_id,valor,plazos_id) VALUES ( ";
                            $sql .=" '{$rango}','{$valor}','".$row['id']."'";
                            $sql .=" ) ;";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] (calc_id,tasas_id,moneda_id) VALUES (";
                            $sql .=" '{$id_calc}',(select max(id) from [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] ),'{$moneda}' ) ;";
//var_dump($sql);

                            $result = $db->sql_query($sql);
                              if ($result){
                                $data['Result'] = "OK";
                                $data['Message'] = "Se a insertado una nueva tasa";

                              }else{
                                $data['Result'] = "ERROR";
                                $data['Message'] = "NO se a insertado la nueva tasa";


                              }

                       } else {
                        $data['Result'] = "ERROR";
                        $data['Message'] = "No se pudo insertar el nuevo plazo";
                      }
            }


      }else{
          
          //rangos nuevos 
               $txt_valor_minimo=$db->sql_escape($parameters['txt_valor_minimo']);
               $txt_valor_maximo=$db->sql_escape($parameters['txt_valor_maximo']);

               $sql  ="";
               $sql .=" SELECT distinct count (1) as exis";
               $sql .=" FROM [".$db_name."].[dbo].[".$db_table_prefix."rangos] r ";
               $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.rangos_id=r.id";
               $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id_calc." and tc.moneda_id=".$moneda;
               $sql .=" where  (r.min between ".$txt_valor_minimo." and ".$txt_valor_maximo.") or (r.max between ".$txt_valor_minimo." and ".$txt_valor_maximo.")";

             $result = $db->sql_query($sql);
             $row= $db->sql_fetchrow($result);
          

             if ($row['exis']>0){
                $data['Result']="ERROR";
                $data['Message'] = "Ya existe un rango que incluye estos valores";

                  }else{
                      
                       $sql ="";
              $sql .="INSERT INTO   [".$db_name."].[dbo].[".$db_table_prefix."rangos] (min,max)  ";
              $sql .= "OUTPUT Inserted.ID as id";
              $sql .=" VALUES ('{$txt_valor_minimo}','{$txt_valor_maximo}')";

              $result = $db->sql_query($sql);

                   if ($result) {
                        $row= $db->sql_fetchrow($result);

                          $sql  ="";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasas_new]  ";
                                 if (strlen($plazo)>0){
                            $sql .=" (rangos_id,valor,plazos_id) VALUES ( '".$row['id']."','{$valor}','{$plazo}'";
                              }else{
                            $sql .=" (rangos_id,valor) VALUES ( '".$row['id']."','{$valor}' ";
                              }
                            $sql .=" ) ;";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] (calc_id,tasas_id,moneda_id) VALUES (";
                            $sql .=" '{$id_calc}',(select max(id) from [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] ),'{$moneda}' ) ;";
                              //var_dump($sql);

                              $result = $db->sql_query($sql);
                              if ($result){
                                $data['Result'] = "OK";
                                $data['Message'] = "Se a insertado una nueva tasa";

                              }else{
                                $data['Result'] = "ERROR";
                                $data['Message'] = "No se a insertado la nueva tasa";


                              }


                       } else {
                        $data['Result'] = "ERROR";
                        $data['Message'] = "No se pudo insertar el nuevo rango";
                      }
                      
                  }

      }


        break;
    case 2:
        
        
          if (in_array("periode", $total_nuevos) ){
          
          //periodo y rangos nuevos 
        
           $txt_description_period=$db->sql_escape($parameters['txt_description_period']);
           $txt_valor_day_value=$db->sql_escape($parameters['txt_valor_day_value']);

            $sql = " SELECT  DISTINCT count(1) as exis FROM  [".$db_name."].[dbo].[".$db_table_prefix."type_period] as tp";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."plazos] p on tp.id=p.type_period_id";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.plazos_id=p.id";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id_calc; 
            $sql .= " where tp.day_value=".$txt_valor_day_value;
        


             $result = $db->sql_query($sql);
             $row= $db->sql_fetchrow($result);
          

             if ($row['exis']>0){
                $data['Result']="ERROR";
                $data['Message'] = "Ya existe un periodo con ese valor de dia";
                return $data;
                  }else{

              $sql ="";
              $sql .="INSERT INTO   [".$db_name."].[dbo].[".$db_table_prefix."type_period] (description,day_value)  ";
              $sql .= "OUTPUT Inserted.ID as id";
              $sql .=" VALUES ('{$txt_description_period}','{$txt_valor_day_value}')";

              $result = $db->sql_query($sql);

                   if ($result) {
                         $row= $db->sql_fetchrow($result);
                         $txt_valor_plazo=$db->sql_escape($parameters['txt_valor_plazo']);
                        
                        
                        $sql ="";
                        $sql .="INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."plazos] (type_period_id,value)";
                        $sql .= "OUTPUT Inserted.ID as id";
                        $sql .=" VALUES (".$row['id'].",'{$txt_valor_plazo}')";
                        
                          $result = $db->sql_query($sql);

                   if ($result) {
                         $row= $db->sql_fetchrow($result);

                          $sql  ="";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] (rangos_id,valor,plazos_id) VALUES ( ";
                            $sql .=" '{$rango}','{$valor}','".$row['id']."'";
                            $sql .=" ) ;";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] (calc_id,tasas_id,moneda_id) VALUES (";
                            $sql .=" '{$id_calc}',(select max(id) from [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] ),'{$moneda}' ) ;";
                          //  var_dump($sql);

                            $result = $db->sql_query($sql);
                              if ($result){
                                $data['Result'] = "OK";
                                $data['Message'] = "Se a insertado una nueva tasa";

                              }else{
                                $data['Result'] = "ERROR";
                                $data['Message'] = "NO se a insertado la nueva tasa";


                              }
                            }else{
                                  $data['Result'] = "ERROR";
                                  $data['Message'] = "No se pudo insertar el nuevo plazo";
                                
                            }

                       } else {
                        $data['Result'] = "ERROR";
                        $data['Message'] = "No se pudo insertar el nuevo periodo";
                      }
            }


      }else{
          
          //rangos nuevos 
               $txt_valor_minimo=$db->sql_escape($parameters['txt_valor_minimo']);
               $txt_valor_maximo=$db->sql_escape($parameters['txt_valor_maximo']);

 
  
 
 

               $sql  ="";
               $sql .=" SELECT distinct count (1) as exis";
               $sql .=" FROM [".$db_name."].[dbo].[".$db_table_prefix."rangos] r ";
               $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.rangos_id=r.id";
               $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id_calc." and tc.moneda_id=".$moneda;
               $sql .=" where  (r.min between ".$txt_valor_minimo." and ".$txt_valor_maximo.") or (r.max between ".$txt_valor_minimo." and ".$txt_valor_maximo.")";

             $result = $db->sql_query($sql);
             $row= $db->sql_fetchrow($result);
          

             if ($row['exis']>0){
                $data['Result']="ERROR";
                $data['Message'] = "Ya existe un rango que incluye estos valores";

                  }else{
                      
                       $sql ="";
              $sql .="INSERT INTO   [".$db_name."].[dbo].[".$db_table_prefix."rangos] (min,max)  ";
              $sql .= "OUTPUT Inserted.ID as id";
              $sql .=" VALUES ('{$txt_valor_minimo}','{$txt_valor_maximo}')";

              $result = $db->sql_query($sql);

                   if ($result) {
                        $row= $db->sql_fetchrow($result);

                          $sql  ="";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] ";
                                 if (strlen($plazo)>0){
                            $sql .=" (rangos_id,valor,plazos_id) VALUES (  '".$row['id']."','{$valor}','{$plazo}'";
                              }else{
                            $sql .=" (rangos_id,valor) VALUES ( '".$row['id']."','{$valor}' ";
                              }
                            $sql .=" ) ;";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] (calc_id,tasas_id,moneda_id) VALUES (";
                            $sql .=" '{$id_calc}',(select max(id) from [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] ),'{$moneda}' ) ;";
                                 //   var_dump($sql);

                              $result = $db->sql_query($sql);
                              if ($result){
                                $data['Result'] = "OK";
                                $data['Message'] = "Se a insertado una nueva tasa";

                              }else{
                                $data['Result'] = "ERROR";
                                $data['Message'] = "No se a insertado la nueva tasa";


                              }


                       } else {
                        $data['Result'] = "ERROR";
                        $data['Message'] = "No se pudo insertar el nuevo rango";
                      }
                      
                  }

      }
        
        
        
        
        
        
        
        
        break;
    case 3:
        
      
                  //rangos nuevos 
               $txt_valor_minimo=$db->sql_escape($parameters['txt_valor_minimo']);
               $txt_valor_maximo=$db->sql_escape($parameters['txt_valor_maximo']);

               $sql  ="";
               $sql .=" SELECT distinct count (1) as exis";
               $sql .=" FROM [".$db_name."].[dbo].[".$db_table_prefix."rangos] r ";
               $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.rangos_id=r.id";
               $sql .=" inner join [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id_calc." and tc.moneda_id=".$moneda;
               $sql .=" where  (r.min between ".$txt_valor_minimo." and ".$txt_valor_maximo.") or (r.max between ".$txt_valor_minimo." and ".$txt_valor_maximo.")";

             $result = $db->sql_query($sql);
             $row= $db->sql_fetchrow($result);
          

             if ($row['exis']>0){
                $data['Result']="ERROR";
                $data['Message'] = "Ya existe un rango que incluye estos valores";

                  }else{
                      
                       $sql ="";
              $sql .="INSERT INTO   [".$db_name."].[dbo].[".$db_table_prefix."rangos] (min,max)  ";
              $sql .= "OUTPUT Inserted.ID as id";
              $sql .=" VALUES ('{$txt_valor_minimo}','{$txt_valor_maximo}')";

              $result = $db->sql_query($sql);

                   if ($result) {
                        $row_rango= $db->sql_fetchrow($result);
                        //  perido and plazo
                                  
          //periodo y rangos nuevos 
        
           $txt_description_period=$db->sql_escape($parameters['txt_description_period']);
           $txt_valor_day_value=$db->sql_escape($parameters['txt_valor_day_value']);

            $sql = " SELECT  DISTINCT count(1) as exis FROM  [".$db_name."].[dbo].[".$db_table_prefix."type_period] as tp";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."plazos] p on tp.id=p.type_period_id";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] tn on tn.plazos_id=p.id";
            $sql .= " INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] tc on tn.id=tc.tasas_id and tc.calc_id=".$id_calc; 
            $sql .= " where tp.day_value=".$txt_valor_day_value;
        


             $result = $db->sql_query($sql);
             $row= $db->sql_fetchrow($result);
          

             if ($row['exis']>0){
                $data['Result']="ERROR";
                $data['Message'] = "Ya existe un periodo con ese valor de dia";
                return $data;
                  }else{

              $sql ="";
              $sql .="INSERT INTO   [".$db_name."].[dbo].[".$db_table_prefix."type_period] (description,day_value)  ";
              $sql .= "OUTPUT Inserted.ID as id";
              $sql .=" VALUES ('{$txt_description_period}','{$txt_valor_day_value}')";

              $result = $db->sql_query($sql);

                   if ($result) {
                         $row_period= $db->sql_fetchrow($result);
                         $txt_valor_plazo=$db->sql_escape($parameters['txt_valor_plazo']);
                        
                        
                        $sql ="";
                        $sql .="INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."plazos] (type_period_id,value)";
                        $sql .= "OUTPUT Inserted.ID as id";
                        $sql .=" VALUES (".$row_period['id'].",'{$txt_valor_plazo}')";
                        
                          $result = $db->sql_query($sql);

                   if ($result) {
                         $row_plazos= $db->sql_fetchrow($result);

                          $sql  ="";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] (rangos_id,valor,plazos_id) VALUES ( ";
                            $sql .=" '".$row_rango['id']."','{$valor}','".$row_plazos['id']."'";
                            $sql .=" ) ;";
                            $sql .=" INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] (calc_id,tasas_id,moneda_id) VALUES (";
                            $sql .=" '{$id_calc}',(select max(id) from [".$db_name."].[dbo].[".$db_table_prefix."tasas_new] ),'{$moneda}' ) ;";
                            var_dump($sql);

                            $result = $db->sql_query($sql);
                              if ($result){
                                $data['Result'] = "OK";
                                $data['Message'] = "Se a insertado una nueva tasa";

                              }else{
                                $data['Result'] = "ERROR";
                                $data['Message'] = "NO se a insertado la nueva tasa";


                              }
                            }else{
                                  $data['Result'] = "ERROR";
                                  $data['Message'] = "No se pudo insertar el nuevo plazo";
                                
                            }

                       } else {
                        $data['Result'] = "ERROR";
                        $data['Message'] = "No se pudo insertar el nuevo periodo";
                      }
            }
                        
                        

                 

                       } else {
                        $data['Result'] = "ERROR";
                        $data['Message'] = "No se pudo insertar el nuevo rango";
                      }
                      
                  }

        
        
        
        break;
}


     }

         
     return $data;

     
}

//end create tasa 


// delete tasa dina 

function deleteParamDinaDataBase($parameters){
    global $db, $db_table_prefix,$db_name;
    $data = array();
  
    
     $id=$db->sql_escape($parameters['id']);
     $id_calc=$db->sql_escape($parameters['id_calc']);



     //elimino primero los tags
     $sql = "DELETE  FROM [".$db_name."].[dbo].[".$db_table_prefix."tasa_calc] WHERE tasas_id=".$id." AND calc_id=".$id_calc.";";
     $sql .= " DELETE  FROM  [".$db_name."].[dbo].[".$db_table_prefix."tasas_new]  WHERE id='".$id."'";

     
     $result = $db->sql_query($sql);


    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos eliminados exitósamente";
    } else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron eliminar los datos";
            }

   
    return $data;

}
//end delete tasa dina 






?>