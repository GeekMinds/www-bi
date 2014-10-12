<?php
function createCarruselHistoryDataBase($parameters){
    global $db, $db_table_prefix,$db_name,$loggedInUser;
    $data = array();

    $carrousel_content_id=$db->sql_escape($parameters["carrousel_content_id"]);
    
    $sql="SELECT carrousel_content_id FROM [".$db_name."].[dbo].[".$db_table_prefix."report_carrusel]
    WHERE carrousel_content_id='".$carrousel_content_id."'";
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrow($result);

    if($result){
        $sql="UPDATE [".$db_name."].[dbo].[".$db_table_prefix."report_carrusel] 
        SET 
        shares=(shares+1),last_updated=getdate()
        WHERE 
        carrousel_content_id='".$carrousel_content_id."'";
    }else{
        $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."report_carrusel]
        (
            carrousel_content_id, shares, last_updated
        )
        OUTPUT Inserted.ID
        VALUES(
        '".$carrousel_content_id."','1', getdate()
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
function listCarruselDataBase($parameters){
        global $db,$db_table_prefix, $db_name; 
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 's.title_es ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';

    $num_filter =intval( $db->sql_escape($parameters["filter"]) );
    $site= intval($db->sql_escape($parameters["site"]) );
    $page= intval( $db->sql_escape($parameters["page"]));
    $module=intval( $db->sql_escape($parameters["module"]) );


    $data = array();
    $sql = "";
    $sql_size = "";
    $condition_ext ="";

if (strpos($parameters['jtsorting'],'s.')==false ) {


        if (strpos($parameters['jtsorting'],'ASC') ) {

        $parameters['jtsorting']= trim( str_replace("ASC","",  $parameters['jtsorting']  ) ) ;
        $concat_post =' ASC';
         
        }else{

        $parameters['jtsorting']= trim( str_replace("DESC","",  $parameters['jtsorting'] ) ) ;
        $concat_post =' DESC';

        }

}


switch ($parameters['jtsorting']) {
  case 'title_site':

    $parameters['jtsorting']="s.title_es".$concat_post;
  break;

  case 'title_page':
    $parameters['jtsorting']="p.title_es".$concat_post;
  break;

  case 'title_carrusel':
    $parameters['jtsorting']="mc.name_es".$concat_post;
  break;

   
}


    $site_id=$db->sql_escape($parameters['site_id']);
    $page_id=$db->sql_escape($parameters['page_id']);
    $carrusel_id=$db->sql_escape($parameters['carrusel_id']);
    

    $site_where="";
    $page_where="";
    $carrusel_where="";

    $site_id=str_replace('null', '',$site_id);
    if(strcmp($site_id,'')!=0){
        $site_where="and s.id='".$site_id."' ";
    }

    $page_id=str_replace('null', '',$page_id);
    if(strcmp($page_id,'')!=0){
        $page_where=" and pc.page_id='".$page_id."' ";
    }

    $carrusel_id=str_replace('null', '',$carrusel_id);
    if(strcmp($carrusel_id,'')!=0){
        $carrusel_where=" and mc.id='".$carrusel_id."' ";
    }

    
    
    
     

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM
                (
                SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].",p.title_es ASC, mc.name_es ASC) AS Row,mc.id,s.title_es as title_site, p.title_es as title_page,c.title_es,mc.name_es as title_carrusel FROM [".$db_name."].[dbo].[".$db_table_prefix."site] s 
                INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page] p ON 
                s.id=p.site_id ".$site_where."
                INNER JOIN  [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc ON 
                pc.page_id=p.id ".$page_where."
                INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."content] c ON
                c.id=pc.content_id AND c.module_id=5 
                INNER JOIN
                [".$db_name."].[dbo].[".$db_table_prefix."module_carrousel] mc
                on
                mc.content_id=c.id".$carrusel_where."
                )  AS user_with_numbers
               WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{

            $sql = "SELECT mc.id,s.title_es as title_site, p.title_es as title_page,c.title_es,mc.name_es as title_carrusel FROM [".$db_name."].[dbo].[".$db_table_prefix."site] s 
                INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page] p ON 
                s.id=p.site_id ".$site_where."
                INNER JOIN  [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc ON 
                pc.page_id=p.id ".$page_where."
                INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."content] c ON
                c.id=pc.content_id AND c.module_id=5 
                INNER JOIN
                [".$db_name."].[dbo].[".$db_table_prefix."module_carrousel] mc
                on
                mc.content_id=c.id".$carrusel_where;
           }
            
            
            $sql_count="SELECT COUNT(*) as total FROM [".$db_name."].[dbo].[".$db_table_prefix."site] s 
                INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page] p ON 
                s.id=p.site_id ".$site_where."
                INNER JOIN  [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc ON 
                pc.page_id=p.id ".$page_where."
                INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."content] c ON
                c.id=pc.content_id AND c.module_id=5 
                INNER JOIN
                [".$db_name."].[dbo].[".$db_table_prefix."module_carrousel] mc
                on
                mc.content_id=c.id".$carrusel_where;

            
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


//You can get the list sites with filter options
function listApprovalDetailDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $loggedInUser;
  
    $data = array();
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'cc.type ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    $parameters['carrusel_id'] = (isset($parameters['carrusel_id'])) ? $parameters['carrusel_id'] : '0';    

    $type_content_id=$db->sql_escape($parameters['type_content_id']);
    $type_content_where="";
    $type_content_id=str_replace('0', '',$type_content_id);
    if(strcmp($type_content_id,'')!=0){
        $type_content_where="and cc.type='".$type_content_id."' ";
    }
    //var_dump($type_content_where.' '.$type_content_id);
  $sql = "";
  $sql_size = "";
  if($parameters['jtpagesize'] != ''){
    $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
    $sql = "SELECT * FROM
          (
            SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, 
                cc.id as content_id,cc.url_media AS url_content, cc.type AS type,ISNULL(
        (SELECT shares 
        FROM [".$db_name."].[dbo].[".$db_table_prefix."report_carrusel]
            WHERE
                carrousel_content_id = cc.id),0) AS shares
    FROM [".$db_name."].[dbo].[".$db_table_prefix."carrousel_content] cc
    WHERE cc.module_carrousel_id='".$db->sql_escape($parameters['carrusel_id'])."' ".$type_content_where."
          )
          AS user_with_numbers
        WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize'];
  }else{
    $sql = "SELECT cc.id as content_id,cc.url_media AS url_content, cc.type AS type,ISNULL(
        (SELECT shares 
        FROM [".$db_name."].[dbo].[".$db_table_prefix."report_carrusel]
            WHERE
                carrousel_content_id = cc.id),0) AS shares
    FROM [".$db_name."].[dbo].[".$db_table_prefix."carrousel_content] cc
    WHERE cc.module_carrousel_id='".$db->sql_escape($parameters['carrusel_id'])."' ".$type_content_where;
  }
  
  $sql_count = "SELECT COUNT(*) total 
    FROM [".$db_name."].[dbo].[".$db_table_prefix."carrousel_content] cc 
    WHERE cc.module_carrousel_id='".$db->sql_escape($parameters['carrusel_id'])."' ".$type_content_where; 
  

  //error_log("La consulta>".$sql);
  $result = $db->sql_query($sql);
  $result_count = $db->sql_query($sql_count);

  $result= $db->sql_fetchrowset($result);
  $result_count= $db->sql_fetchrow($result_count);
  //dumpear($result);
  $data["rows"] = $result;
  $data["count"] = $result_count['total'];
  //$data['sql']=$sql_count;
  return $data;
}




//You can get the list sites 
function listApprovalDetailTotalDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $loggedInUser;
  
    $data = array();
   

    $type_content_id=$db->sql_escape($parameters['type_content_id']);
    $type_content_where="";
    $type_content_id=str_replace('0', '',$type_content_id);
    if(strcmp($type_content_id,'')!=0){
        $type_content_where="and cc.type='".$type_content_id."' ";
    }

   $sql = "";
   $sql = "SELECT cc.id as content_id,cc.module_carrousel_id ,cc.url_media AS url_content, cc.type AS type,ISNULL(
        (SELECT shares 
        FROM [".$db_name."].[dbo].[".$db_table_prefix."report_carrusel]
            WHERE
                carrousel_content_id = cc.id),0) AS shares
    FROM [".$db_name."].[dbo].[".$db_table_prefix."carrousel_content] cc
    WHERE cc.module_carrousel_id IN (".$db->sql_escape($parameters['ids']).")  ".$type_content_where;
  


  
  $result = $db->sql_query($sql);
  $result= $db->sql_fetchrowset($result);
  $data["result"] = $result;
  
  
  return $data;
}




///Consultas de filtros
function getSiteDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 

        $sql="SELECT s.id, s.title_es as description FROM [".$db_name."].[dbo].[".$db_table_prefix."site] s 
        INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page] p ON 
        s.id=p.site_id
        INNER JOIN  [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc ON 
        pc.page_id=p.id 
        INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."content] c ON
        c.id=pc.content_id AND c.module_id=5 GROUP BY s.id, s.title_es";

            //$sql  = " SELECT  id, title_es as description ";
            //$sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."site] s order by description";
           
        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);

    return $result;
}

function getPageDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
            
        $sql="SELECT p.id, p.title_es as description FROM [".$db_name."].[dbo].[".$db_table_prefix."page] p
        INNER JOIN  [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc ON 
        pc.page_id=p.id AND p.site_id='".$parameters["id_site"]."'
        INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."content] c ON
        c.id=pc.content_id AND c.module_id=5 GROUP BY p.id, p.title_es ORDER BY p.title_es";
            //var_dump($sql);
        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);

            
    return $result;
}

function getCarruselDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
        

        $sql="SELECT mc.id, mc.name_es description, mc.content_id 
            FROM 
            [".$db_name."].[dbo].[".$db_table_prefix."module_carrousel] mc
            INNER JOIN
            [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc
            ON
            mc.content_id=pc.content_id 
            WHERE pc.page_id='".$parameters["id_page"]."' 
            group by mc.id, mc.name_es, mc.content_id
            order by mc.name_es ";
        //$sql="select id, name_es description from module_carrousel order by name_es";
            //var_dump($sql);
        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);
    
    return $result;
}







?>