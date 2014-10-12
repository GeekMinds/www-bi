<?php
function createFormHistoryDataBase($parameters){
    global $db, $db_table_prefix,$db_name,$loggedInUser;
    $data = array();

    $form_id=$db->sql_escape($parameters["form_id"]);
    $metadata=$db->sql_escape($parameters["metadata"]);
    if(isset($metadata)){
        $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."report_form]
        (
            form_id, metadata, created_at
        )
        OUTPUT Inserted.ID
        VALUES(
        '".$form_id."','".$metadata."', getdate()
        )"; 
    }else{
        $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."report_form]
        (
            form_id, created_at
        )
        OUTPUT Inserted.ID
        VALUES(
        '".$form_id."', getdate()
        )"; 
    }
                 


    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrow($result);
    

    if ($result) {
        $data['error'] = "0";
        $data['msj'] = "Datos insertados";
        $data['user_id']=$loggedInUser->user_id;
    }else {
        $data['error'] = "100";
        $data['msj'] = "No se pudieron insertar los datos";
    }
    return $data;
}

//OBTIENE PROMOCIONES DEL BUSCADOR
function listFormDataBase($parameters){
        global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'mf.name_es ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';

    
    


    $data = array();
    $sql = "";
    $sql_size = "";
    $condition_ext ="";

if (strpos($parameters['jtsorting'],'mf.')==false ) {


        if (strpos($parameters['jtsorting'],'ASC') ) {

        $parameters['jtsorting']= trim( str_replace("ASC","",  $parameters['jtsorting']  ) ) ;
        $concat_post =' ASC';
         
        }else{

        $parameters['jtsorting']= trim( str_replace("DESC","",  $parameters['jtsorting'] ) ) ;
        $concat_post =' DESC';

        }

}


switch ($parameters['jtsorting']) {
  case 'id':

    $parameters['jtsorting']="mf.id".$concat_post;
    break;

  case 'crm_id':
    $parameters['jtsorting']="mf.crm_id".$concat_post;
    break;

  case 'name':
    $parameters['jtsorting']="mf.name_es".$concat_post;
    break;   
}

    $filtros=array();
    $type_form =intval( $db->sql_escape($parameters["type_form"]) );
    $id_form= intval($db->sql_escape($parameters["id_form"]) );
    $id_crm=$db->sql_escape($parameters['id_crm']);
    $date1=$db->sql_escape($parameters['date1']);
    $date2=$db->sql_escape($parameters['date2']);
    

    $type_form_where="";
    $id_form_where="";
    $carrusel_where="";

    //$type_form=str_replace('null', '0',$site_id);
    if($type_form!=0){
        if((int)$type_form==1){
            $filtros[]=" mf.crm_id is not null";
        }else{
            $filtros[]=" mf.crm_id is null";
            $id_crm="";
        }
    }

    if($id_form!=0){
        $filtros[]=" mf.id='".$id_form."' ";
    }

    if(strcmp($id_crm, '')!=0){
        $filtros[]=" mf.crm_id='".$id_crm."' ";
    }

    if(strcmp($date1, '')!=0){
        $filtros[]=" rf.created_at>=convert(datetime,'".$date1." 00:00:00',103) ";
    }

    if(strcmp($date2, '')!=0){
        $filtros[]=" rf.created_at<=convert(datetime,'".$date2." 23:59:59',103) ";
    }

    $sql_where="";

    if(count($filtros)>0){
        for ($i=0; $i < count($filtros) ; $i++) { 
            if($i==0){
                $sql_where=" WHERE ".$filtros[$i];
            }else{
                $sql_where.=" AND ".$filtros[$i];
            }
        }
    }
    
    
     

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM
                (
                SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row,mf.id ,
                ISNULL(mf.crm_id ,'') AS crm_id , name_es as name,
                CASE WHEN ISNULL(mf.crm_id ,'0') <> 0 THEN 'CRM' ELSE 'Local' END AS tipo,
                ISNULL((SELECT count(form_id) FROM [".$db_name."].[dbo].[".$db_table_prefix."report_form] WHERE form_id = mf.id),0) as envios
                FROM
                [".$db_name."].[dbo].[".$db_table_prefix."module_form] mf 
                left join
                [".$db_name."].[dbo].[".$db_table_prefix."report_form] rf
                on
                mf.id=rf.form_id".$sql_where."
                )  AS user_with_numbers
               WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{

            $sql = "SELECT mf.id ,
                ISNULL(mf.crm_id ,'') AS crm_id , name_es as name,
                CASE WHEN ISNULL(mf.crm_id ,'0') <> 0 THEN 'CRM' ELSE 'Local' END AS tipo,
                ISNULL((SELECT count(form_id) FROM [".$db_name."].[dbo].[".$db_table_prefix."report_form] WHERE form_id = mf.id),0) as envios
                FROM
                [".$db_name."].[dbo].[".$db_table_prefix."module_form] mf
                left join
                [".$db_name."].[dbo].[".$db_table_prefix."report_form] rf
                on
                mf.id=rf.form_id".$sql_where;
           }
            
            
            $sql_count="SELECT COUNT(*) as total
                FROM
                [".$db_name."].[dbo].[".$db_table_prefix."module_form] mf
                left join
                [".$db_name."].[dbo].[".$db_table_prefix."report_form] rf
                on
                mf.id=rf.form_id".$sql_where;

            
//var_dump($sql);
//var_dump($sql_size);
              $result = $db->sql_query($sql);
              $result= $db->sql_fetchrowset($result);

              $result_count = $db->sql_query($sql_count);
              $result_count= $db->sql_fetchrow($result_count);
//var_dump($result);

        $data['result'] = $result;
        $data["count"]  = $result_count["total"];

    return $data;
}


function getFormDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
        $type_form =intval( $db->sql_escape($parameters["type_form"]) );
        $type_form_where="";
    //$type_form=str_replace('null', '0',$site_id);
    if($type_form!=0){
        if((int)$type_form==1){
            $type_form_where=" WHERE mf.crm_id is not null";
        }else{
            $type_form_where=" WHERE mf.crm_id is null";
        }
        //$site_where="and s.id='".$site_id."' ";
    }
        $sql="SELECT mf.id, mf.name_es as description FROM [".$db_name."].[dbo].[".$db_table_prefix."module_form] mf".$type_form_where." ORDER BY mf.name_es";
            //var_dump($sql);
        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);
            
    return $result;
}

?>