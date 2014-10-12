<?php
function createPageHistoryDataBase($parameters){
    global $db, $db_table_prefix,$db_name,$loggedInUser;
    $data = array();

    $page_id=$db->sql_escape($parameters["page_id"]);
    
    $sql="SELECT page_id FROM [".$db_name."].[dbo].[".$db_table_prefix."report_page]
    WHERE page_id='".$page_id."'";
    $result = $db->sql_query($sql);
    $result= $db->sql_fetchrow($result);

    if($result){
        $sql="UPDATE [".$db_name."].[dbo].[".$db_table_prefix."report_page] 
        SET 
        shares=(shares+1),last_updated=getdate()
        WHERE 
        page_id='".$page_id."'";
    }else{
        $sql = "INSERT INTO [".$db_name."].[dbo].[".$db_table_prefix."report_page]
        (
            page_id, shares, last_updated
        )
        OUTPUT Inserted.ID
        VALUES(
        '".$page_id."','1', getdate()
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
function listSitePageDataBase($parameters){
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
    $count_where="";
    $page_where="";
    $carrusel_where="";

    $site_id=str_replace('null', '',$site_id);
    if(strcmp($site_id,'')!=0){
        $site_where="and s.id='".$site_id."' ";
        $count_where="WHERE s.id='".$site_id."' ";
    }

    $page_id=str_replace('null', '',$page_id);
    if(strcmp($page_id,'')!=0){
        $page_where="and pc.page_id='".$page_id."' ";
    }

    $carrusel_id=str_replace('null', '',$carrusel_id);
    if(strcmp($carrusel_id,'')!=0){
        $carrusel_where="and mc.id='".$carrusel_id."' ";
    }

    
    
    
     

         if($parameters['jtpagesize'] != ''){
              $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
                //query con paginacion 
               $sql = "SELECT * FROM
                (
                SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row,s.id,s.title_es as title_site
                 FROM [".$db_name."].[dbo].[".$db_table_prefix."site] s 
                INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page] p ON 
                s.id=p.site_id ".$site_where."
                GROUP BY s.id, s.title_es
                )  AS user_with_numbers
               WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize']."";
 
             }else{

            $sql = "SELECT s.id,s.title_es as title_site
                 FROM [".$db_name."].[dbo].[".$db_table_prefix."site] s 
                INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page] p ON 
                s.id=p.site_id ".$site_where."
                GROUP BY s.id, s.title_es";
           }
            
            
            $sql_count="SELECT COUNT(*) as total FROM [".$db_name."].[dbo].[".$db_table_prefix."site] s 
                ".$count_where;

            
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
    $parameters['jtsorting'] = (isset($parameters['jtsorting'])) ? $parameters['jtsorting'] : 'p.title_es ASC ';
    $parameters['jtstartindex'] = (isset($parameters['jtstartindex'])) ? $parameters['jtstartindex'] : '';
    $parameters['carrusel_id'] = (isset($parameters['carrusel_id'])) ? $parameters['carrusel_id'] : '0';    

    $page_id=$db->sql_escape($parameters['page_id']);
    $page_where="";

    $page_id=str_replace('null', '',$page_id);
    if(strcmp($page_id,'')!=0){
        $page_where=" and p.id='".$page_id."' ";
    }
    //var_dump($type_content_where.' '.$type_content_id);
  $sql = "";
  $sql_size = "";
  if($parameters['jtpagesize'] != ''){
    $parameters['jtpagesize'] = (int)$parameters['jtstartindex'] + (int)$parameters['jtpagesize'];
    $sql = "SELECT * FROM
          (
            SELECT ROW_NUMBER() OVER (ORDER BY ".$parameters['jtsorting'].") AS Row, 
                s.id as site_id, p.id as page_id, p.title_es as name_page,
    ISNULL(
        (SELECT shares 
        FROM [".$db_name."].[dbo].[".$db_table_prefix."report_page]
            WHERE
                page_id = p.id),0) AS shares,
    ISNULL(
        (SELECT COUNT(mod_p) 
            FROM [".$db_name."].[dbo].[".$db_table_prefix."comment] c INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."mod_p] mp
            ON mp.id = c.mod_p 
            INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc
            ON pc.content_id = mp.content_id AND pc.page_id = p.id),0)AS comment,
    ISNULL(
    (SELECT
    (CONVERT(DECIMAL(4,2),SUM(rating)) / COUNT(mod_p)) 
FROM
    [".$db_name."].[dbo].[".$db_table_prefix."comment] c INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."mod_p] mp
        ON mp.id = c.mod_p 
        INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc
        ON pc.content_id = mp.content_id AND pc.page_id = p.id),0)AS rating
 FROM
    [".$db_name."].[dbo].[".$db_table_prefix."page] p INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."site] s
        ON s.id = p.site_id and s.id='".$db->sql_escape($parameters['site_id'])."'".$page_where."
          )
          AS user_with_numbers
        WHERE Row > ".$parameters['jtstartindex']." AND Row <= ".$parameters['jtpagesize'];
  }else{
    $sql = "SELECT s.id as site_id, p.id as page_id, p.title_es as name_page,
    ISNULL(
        (SELECT shares 
        FROM [".$db_name."].[dbo].[".$db_table_prefix."report_page]
            WHERE
                page_id = p.id),0) AS shares,
    ISNULL(
        (SELECT COUNT(mod_p) 
            FROM [".$db_name."].[dbo].[".$db_table_prefix."comment] c INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."mod_p] mp
            ON mp.id = c.mod_p 
            INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc
            ON pc.content_id = mp.content_id AND pc.page_id = p.id),0)AS comment,
    ISNULL(
    (SELECT
    (CONVERT(DECIMAL(4,2),SUM(rating)) / COUNT(mod_p)) 
FROM
    [".$db_name."].[dbo].[".$db_table_prefix."comment] c INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."mod_p] mp
        ON mp.id = c.mod_p 
        INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc
        ON pc.content_id = mp.content_id AND pc.page_id = p.id),0)AS rating
 FROM
    [".$db_name."].[dbo].[".$db_table_prefix."page] p INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."site] s
        ON s.id = p.site_id and s.id='".$db->sql_escape($parameters['site_id'])."'".$page_where;
  }
  
  $sql_count = "SELECT COUNT(*) total 
    FROM
    [".$db_name."].[dbo].[".$db_table_prefix."page] p INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."site] s
        ON s.id = p.site_id and s.id='".$db->sql_escape($parameters['site_id'])."'".$page_where; 
  

  //error_log("La consulta>".$sql);
  $result = $db->sql_query($sql);
  $result_count = $db->sql_query($sql_count);

  $result= $db->sql_fetchrowset($result);
  $result_count= $db->sql_fetchrow($result_count);
  //dumpear($result);
  $data["rows"] = $result;
  $data["count"] = $result_count['total'];
  
  return $data;
}





//You can get the list sites with filter options
function listApprovalDetailTotalDataBase($parameters = array()) {
    global $db, $db_table_prefix, $db_name, $loggedInUser;
  
    $data = array();


    $page_id=$db->sql_escape($parameters['page_id']);
    $page_where="";

    $page_id=str_replace('null', '',$page_id);
    if(strcmp($page_id,'')!=0){
        $page_where=" and p.id='".$page_id."' ";
    }
  
  $sql = "";
  

    $sql = "SELECT s.id as site_id, p.id as page_id, p.title_es as name_page,
    ISNULL(
        (SELECT shares 
        FROM [".$db_name."].[dbo].[".$db_table_prefix."report_page]
            WHERE
                page_id = p.id),0) AS shares,
    ISNULL(
        (SELECT COUNT(mod_p) 
            FROM [".$db_name."].[dbo].[".$db_table_prefix."comment] c INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."mod_p] mp
            ON mp.id = c.mod_p 
            INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc
            ON pc.content_id = mp.content_id AND pc.page_id = p.id),0)AS comment,
    ISNULL(
    (SELECT
    (CONVERT(DECIMAL(4,2),SUM(rating)) / COUNT(mod_p)) 
 FROM
    [".$db_name."].[dbo].[".$db_table_prefix."comment] c INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."mod_p] mp
        ON mp.id = c.mod_p 
        INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."page_content] pc
        ON pc.content_id = mp.content_id AND pc.page_id = p.id),0)AS rating

 FROM
    [".$db_name."].[dbo].[".$db_table_prefix."page] p INNER JOIN [".$db_name."].[dbo].[".$db_table_prefix."site] s
        ON s.id = p.site_id and s.id in (".$db->sql_escape($parameters['ids']).") ".$page_where;

        
 
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
        GROUP BY s.id, s.title_es";

            //$sql  = " SELECT  id, title_es as description ";
            //$sql .= " FROM [".$db_name."].[dbo].[".$db_table_prefix."site] s order by description";
           
        $result = $db->sql_query($sql);
        $result = $db->sql_fetchrowset($result);

    return $result;
}

function getPageDataBase($parameters){
    global $db,$db_table_prefix, $db_name; 
            
        $sql="SELECT p.id, p.title_es as description FROM [".$db_name."].[dbo].[".$db_table_prefix."page] p
        WHERE
        p.site_id='".$parameters["id_site"]."'
        ORDER BY p.title_es";
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